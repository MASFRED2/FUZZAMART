<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\KartuStok;
use App\Models\Pemasok;
use App\Models\StokMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = StokMasuk::with(['barang', 'pemasok'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('barang', function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            });
        }

        $stok_masuk = $query->paginate(15)->withQueryString();
        $barang = Barang::aktif()->orderBy('nama_barang')->get();
        $pemasok = Pemasok::orderBy('nama_pemasok')->get();

        return view('admin.stok_masuk.index', compact('stok_masuk', 'barang', 'pemasok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barang,id'],
            'pemasok_id' => ['required', 'exists:pemasok,id'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'tgl_kadaluwarsa' => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($validated) {
            $barang = Barang::where('id', $validated['barang_id'])->lockForUpdate()->firstOrFail();
            $stokSebelum = $barang->stok_total;

            $stokMasuk = StokMasuk::create([
                'barang_id' => $validated['barang_id'],
                'pemasok_id' => $validated['pemasok_id'],
                'jumlah_masuk' => $validated['jumlah_masuk'],
                'jumlah_sisa' => $validated['jumlah_masuk'],
                'harga_beli' => $validated['harga_beli'],
                'tgl_kadaluwarsa' => $validated['tgl_kadaluwarsa'] ?? null,
            ]);

            $barang->increment('stok_total', $validated['jumlah_masuk']);
            $barang->update(['harga_beli_terakhir' => $validated['harga_beli'], 'is_active' => true]);
            $barang->refresh();

            KartuStok::create([
                'barang_id' => $barang->id,
                'user_id' => auth()->id(),
                'tipe' => 'masuk',
                'referensi' => 'STOK-MASUK-' . $stokMasuk->id,
                'qty_masuk' => $validated['jumlah_masuk'],
                'qty_keluar' => 0,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $barang->stok_total,
                'keterangan' => 'Penambahan stok dari pemasok.',
            ]);

            AuditLog::catat('Stok Masuk', 'Tambah', 'Menambah stok ' . $barang->nama_barang . ' sebanyak ' . $validated['jumlah_masuk']);
        });

        return redirect()->back()->with('success', 'Stok masuk berhasil dicatat dan stok barang otomatis bertambah.');
    }
}
