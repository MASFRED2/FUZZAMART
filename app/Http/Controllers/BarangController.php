<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['kategori', 'cabang']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->status === 'nonaktif') {
            $query->where('is_active', false);
        } else {
            $query->aktif();
        }

        $barang = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        $kategori = Kategori::orderBy('nama_kategori')->get();

        return view('admin.barang.index', compact('barang', 'kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'barcode' => ['required', 'string', 'max:100', 'unique:barang,barcode'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'harga_beli_terakhir' => ['nullable', 'numeric', 'min:0'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'stok_total' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['satuan'] = $validated['satuan'] ?? 'pcs';
        $validated['harga_beli_terakhir'] = $validated['harga_beli_terakhir'] ?? 0;
        $validated['stok_total'] = $validated['stok_total'] ?? 0;
        $validated['is_active'] = true;
        $validated['cabang_id'] = auth()->user()->cabang_id;

        $barang = Barang::create($validated);
        AuditLog::catat('Data Barang', 'Tambah', 'Menambah barang ' . $barang->nama_barang);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil disimpan. Tambahkan stok melalui menu Stok Masuk jika barang sudah tersedia fisik.');
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'exists:kategori,id'],
            'barcode' => ['required', 'string', 'max:100', Rule::unique('barang', 'barcode')->ignore($barang->id)],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'harga_beli_terakhir' => ['nullable', 'numeric', 'min:0'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['satuan'] = $validated['satuan'] ?? 'pcs';
        $validated['harga_beli_terakhir'] = $validated['harga_beli_terakhir'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $barang->update($validated);
        AuditLog::catat('Data Barang', 'Ubah', 'Mengubah barang ' . $barang->nama_barang);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $terpakai = $barang->penjualan_detail()->exists()
            || $barang->stok_masuk()->exists()
            || $barang->stock_opname()->exists()
            || $barang->diskon()->exists();

        if ($terpakai) {
            $barang->update(['is_active' => false]);
            $barang->delete();
            AuditLog::catat('Data Barang', 'Arsip', 'Mengarsipkan barang ' . $barang->nama_barang . ' karena sudah memiliki riwayat transaksi/stok.');

            return redirect()->route('barang.index')->with('success', 'Barang sudah memiliki riwayat, sehingga tidak dihapus permanen. Data berhasil diarsipkan agar riwayat transaksi tetap aman.');
        }

        $nama = $barang->nama_barang;
        $barang->delete();
        AuditLog::catat('Data Barang', 'Hapus', 'Menghapus barang ' . $nama);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
