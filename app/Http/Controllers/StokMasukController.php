<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\BarangSatuan;
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
        $barang = Barang::with([
            'satuan_produk' => fn ($unit) => $unit->aktif()->with('satuan')->orderByDesc('is_default')->orderBy('konversi_satuan'),
        ])->aktif()->orderBy('nama_barang')->get();
        $pemasok = Pemasok::orderBy('nama_pemasok')->get();

        return view('admin.stok_masuk.index', compact('stok_masuk', 'barang', 'pemasok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_satuan_id' => ['required', 'exists:barang_satuan,id'],
            'pemasok_id' => ['required', 'exists:pemasok,id'],
            'jumlah_kemasan' => ['required', 'integer', 'min:1'],
            'harga_beli_kemasan' => ['required', 'numeric', 'min:0'],
            'tgl_kadaluwarsa' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        DB::transaction(function () use ($validated) {
            $barangSatuan = BarangSatuan::with('satuan')
                ->aktif()
                ->whereHas('barang', fn ($query) => $query->aktif())
                ->findOrFail($validated['barang_satuan_id']);
            $barang = Barang::where('id', $barangSatuan->barang_id)->lockForUpdate()->firstOrFail();
            $konversi = max(1, (int) $barangSatuan->konversi_satuan);
            $jumlahKemasan = (int) $validated['jumlah_kemasan'];
            $jumlahDasar = $jumlahKemasan * $konversi;
            $hargaBeliKemasan = (float) $validated['harga_beli_kemasan'];
            $hargaBeliDasar = $hargaBeliKemasan / $konversi;
            $stokSebelum = $barang->stok_total;

            $stokMasuk = StokMasuk::create([
                'barang_id' => $barang->id,
                'barang_satuan_id' => $barangSatuan->id,
                'pemasok_id' => $validated['pemasok_id'],
                'jumlah_masuk' => $jumlahDasar,
                'jumlah_sisa' => $jumlahDasar,
                'jumlah_kemasan' => $jumlahKemasan,
                'satuan_masuk' => $barangSatuan->satuan?->simbol ?? $barang->satuan,
                'konversi_satuan' => $konversi,
                'harga_beli' => $hargaBeliDasar,
                'harga_beli_kemasan' => $hargaBeliKemasan,
                'tgl_kadaluwarsa' => $validated['tgl_kadaluwarsa'] ?? null,
            ]);

            $barang->increment('stok_total', $jumlahDasar);
            $barang->update(['harga_beli_terakhir' => $hargaBeliDasar, 'is_active' => true]);
            $barang->refresh();

            KartuStok::create([
                'barang_id' => $barang->id,
                'user_id' => auth()->id(),
                'tipe' => 'masuk',
                'referensi' => 'STOK-MASUK-' . $stokMasuk->id,
                'qty_masuk' => $jumlahDasar,
                'qty_keluar' => 0,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $barang->stok_total,
                'keterangan' => "Penerimaan {$jumlahKemasan} ".($barangSatuan->satuan?->simbol ?? $barang->satuan)." ({$jumlahDasar} {$barang->satuan}) dari pemasok.",
            ]);

            AuditLog::catat('Stok Masuk', 'Tambah', 'Menambah stok '.$barang->nama_barang.' sebanyak '.$jumlahKemasan.' '.($barangSatuan->satuan?->simbol ?? $barang->satuan).'.');
        });

        return redirect()->back()->with('success', 'Stok masuk berhasil dicatat dan stok barang otomatis bertambah.');
    }
}
