<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\BarangSatuan;
use App\Models\Kategori;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with([
            'kategori',
            'cabang',
            'satuan_dasar',
            'satuan_produk' => fn ($query) => $query->aktif()->with('satuan')->orderByDesc('is_default')->orderBy('konversi_satuan'),
        ]);

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%")
                    ->orWhereHas('satuan_produk', fn ($unit) => $unit->where('barcode', 'like', "%{$q}%"));
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
        $satuan = Satuan::aktif()->orderBy('nama_satuan')->get();

        return view('admin.barang.index', compact('barang', 'kategori', 'satuan'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateBarang($request);

        $barang = DB::transaction(function () use ($validated) {
            $satuanDasar = Satuan::findOrFail($validated['satuan_dasar_id']);

            $barang = Barang::create([
                'kategori_id' => $validated['kategori_id'],
                'cabang_id' => auth()->user()->cabang_id,
                'barcode' => $validated['barcode'],
                'nama_barang' => $validated['nama_barang'],
                'satuan' => $satuanDasar->simbol,
                'stok_total' => 0,
                'stok_minimal' => $validated['stok_minimal'],
                'harga_jual' => $validated['harga_jual'],
                'harga_beli_terakhir' => $validated['harga_beli_terakhir'] ?? 0,
                'is_active' => true,
            ]);

            $this->sinkronkanSatuan($barang, $validated);

            return $barang;
        });

        AuditLog::catat('Data Barang', 'Tambah', 'Menambah barang '.$barang->nama_barang.' beserta satuan jualnya.');

        return redirect()->route('barang.index')->with('success', 'Barang dan harga per satuan berhasil disimpan. Stok awal wajib dicatat melalui menu Stok Masuk.');
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $this->validateBarang($request, $barang);

        DB::transaction(function () use ($barang, $validated) {
            $satuanDasar = Satuan::findOrFail($validated['satuan_dasar_id']);

            $barang->update([
                'kategori_id' => $validated['kategori_id'],
                'barcode' => $validated['barcode'],
                'nama_barang' => $validated['nama_barang'],
                'satuan' => $satuanDasar->simbol,
                'harga_jual' => $validated['harga_jual'],
                'harga_beli_terakhir' => $validated['harga_beli_terakhir'] ?? 0,
                'stok_minimal' => $validated['stok_minimal'],
                'is_active' => $validated['is_active'],
            ]);

            $this->sinkronkanSatuan($barang, $validated);
        });

        AuditLog::catat('Data Barang', 'Ubah', 'Mengubah barang '.$barang->nama_barang.' beserta satuan jualnya.');

        return redirect()->route('barang.index')->with('success', 'Data barang dan harga per satuan berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $terpakai = $barang->penjualan_detail()->exists()
            || $barang->stok_masuk()->exists()
            || $barang->stock_opname()->exists()
            || $barang->diskon()->exists();

        if ($terpakai) {
            $barang->update(['is_active' => false]);
            $barang->satuan_produk()->update(['is_active' => false]);
            $barang->delete();
            AuditLog::catat('Data Barang', 'Arsip', 'Mengarsipkan barang '.$barang->nama_barang.' karena sudah memiliki riwayat transaksi/stok.');

            return redirect()->route('barang.index')->with('success', 'Barang sudah memiliki riwayat sehingga diarsipkan, bukan dihapus permanen.');
        }

        $nama = $barang->nama_barang;
        $barang->satuan_produk()->update(['is_active' => false]);
        $barang->delete();
        AuditLog::catat('Data Barang', 'Hapus', 'Menghapus barang '.$nama);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function validateBarang(Request $request, ?Barang $barang = null): array
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => ['required', 'exists:kategori,id'],
            'barcode' => [
                'required',
                'string',
                'max:100',
                Rule::unique('barang', 'barcode')->ignore($barang?->id),
            ],
            'nama_barang' => ['required', 'string', 'max:255'],
            'satuan_dasar_id' => ['required', 'exists:satuan,id'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'harga_beli_terakhir' => ['nullable', 'numeric', 'min:0'],
            'stok_minimal' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'kemasan' => ['nullable', 'array'],
            'kemasan.*.satuan_id' => ['required', 'exists:satuan,id'],
            'kemasan.*.barcode' => ['required', 'string', 'max:100'],
            'kemasan.*.konversi_satuan' => ['required', 'integer', 'min:2'],
            'kemasan.*.harga_jual' => ['required', 'numeric', 'min:0'],
        ], [
            'kemasan.*.konversi_satuan.min' => 'Konversi kemasan tambahan minimal 2 satuan dasar.',
        ]);

        $validator->after(function ($validator) use ($request, $barang) {
            $kemasan = collect($request->input('kemasan', []))->filter(fn ($item) => is_array($item));
            $barcode = $kemasan->pluck('barcode')->filter()->map(fn ($value) => trim((string) $value));
            $satuanId = $kemasan->pluck('satuan_id')->filter()->map(fn ($value) => (int) $value);
            $barcodeDasar = trim((string) $request->input('barcode'));
            $satuanDasarId = (int) $request->input('satuan_dasar_id');

            if ($barcode->contains($barcodeDasar) || $barcode->duplicates()->isNotEmpty()) {
                $validator->errors()->add('kemasan', 'Barcode satuan dasar dan seluruh kemasan harus berbeda.');
            }

            if ($satuanId->contains($satuanDasarId) || $satuanId->duplicates()->isNotEmpty()) {
                $validator->errors()->add('kemasan', 'Satu jenis satuan hanya boleh dipakai sekali pada setiap barang.');
            }

            $semuaBarcode = $barcode->push($barcodeDasar)->filter()->unique();
            foreach ($semuaBarcode as $kode) {
                $dipakaiBarangLain = Barang::withTrashed()
                    ->where('barcode', $kode)
                    ->when($barang, fn ($query) => $query->where('id', '!=', $barang->id))
                    ->exists();

                $dipakaiSatuanLain = BarangSatuan::where('barcode', $kode)
                    ->when($barang, fn ($query) => $query->where('barang_id', '!=', $barang->id))
                    ->exists();

                if ($dipakaiBarangLain || $dipakaiSatuanLain) {
                    $validator->errors()->add('kemasan', "Barcode {$kode} sudah digunakan oleh barang lain.");
                }
            }

            if ($barang) {
                $satuanDasarLama = $barang->satuan_dasar()->value('satuan_id');
                $memilikiRiwayat = $barang->stok_total > 0
                    || $barang->stok_masuk()->exists()
                    || $barang->penjualan_detail()->exists();

                if ($satuanDasarLama && $memilikiRiwayat && $satuanDasarLama !== $satuanDasarId) {
                    $validator->errors()->add('satuan_dasar_id', 'Satuan dasar tidak dapat diganti karena barang sudah memiliki stok atau riwayat transaksi.');
                }
            }
        });

        $validated = $validator->validate();
        $validated['is_active'] = $barang ? $request->boolean('is_active') : true;
        $validated['kemasan'] = collect($validated['kemasan'] ?? [])
            ->map(function ($item) {
                $item['barcode'] = trim($item['barcode']);

                return $item;
            })
            ->values()
            ->all();

        return $validated;
    }

    private function sinkronkanSatuan(Barang $barang, array $validated): void
    {
        $barang->satuan_produk()->update([
            'barcode' => null,
            'is_default' => false,
            'is_active' => false,
        ]);

        $satuanData = [[
            'satuan_id' => (int) $validated['satuan_dasar_id'],
            'barcode' => trim($validated['barcode']),
            'konversi_satuan' => 1,
            'harga_jual' => $validated['harga_jual'],
            'is_default' => true,
        ]];

        foreach ($validated['kemasan'] as $kemasan) {
            $satuanData[] = [
                'satuan_id' => (int) $kemasan['satuan_id'],
                'barcode' => trim($kemasan['barcode']),
                'konversi_satuan' => (int) $kemasan['konversi_satuan'],
                'harga_jual' => $kemasan['harga_jual'],
                'is_default' => false,
            ];
        }

        foreach ($satuanData as $item) {
            $barang->satuan_produk()->updateOrCreate(
                ['satuan_id' => $item['satuan_id']],
                [...$item, 'is_active' => true]
            );
        }
    }
}
