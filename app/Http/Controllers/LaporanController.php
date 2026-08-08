<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\StokMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function expired()
    {
        $data = StokMasuk::with('barang')
            ->whereNotNull('tgl_kadaluwarsa')
            ->whereDate('tgl_kadaluwarsa', '<=', now()->addDays(30))
            ->orderBy('tgl_kadaluwarsa', 'asc')
            ->get();

        return view('admin.laporan.expired', compact('data'));
    }

    public function analitik()
    {
        $slowMoving = Barang::with('kategori')
            ->withCount('penjualan_detail')
            ->aktif()
            ->orderBy('penjualan_detail_count', 'asc')
            ->take(20)
            ->get();

        $totalTerjualSub = DB::table('penjualan_detail')
            ->select(
                'barang_id',
                DB::raw('COALESCE(SUM(qty), 0) as total_terjual')
            )
            ->groupBy('barang_id');

        $terlaris = Barang::with('kategori')
            ->leftJoinSub($totalTerjualSub, 'pd', function ($join) {
                $join->on('barang.id', '=', 'pd.barang_id');
            })
            ->select(
                'barang.*',
                DB::raw('COALESCE(pd.total_terjual, 0) as total_terjual')
            )
            ->where('barang.is_active', true)
            ->orderByDesc('total_terjual')
            ->take(10)
            ->get();

        return view('admin.laporan.analitik', compact('slowMoving', 'terlaris'));
    }

    public function stokRendah()
    {
        $barangKritis = Barang::with('kategori')
            ->aktif()
            ->whereRaw('stok_total <= stok_minimal')
            ->orderBy('stok_total', 'asc')
            ->get();

        return view('admin.laporan.stok_rendah', compact('barangKritis'));
    }

    public function penjualan(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $penjualan = Penjualan::with('kasir')
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->latest()
            ->get();

        $totalPenjualan = $penjualan->where('status_pembayaran', 'sukses')->sum('total_harga');
        $jumlahTransaksi = $penjualan->where('status_pembayaran', 'sukses')->count();
        $totalRetur = $penjualan->where('status_pembayaran', 'retur')->sum('total_harga');

        return view('admin.laporan.penjualan', compact('penjualan', 'tanggalAwal', 'tanggalAkhir', 'totalPenjualan', 'jumlahTransaksi', 'totalRetur'));
    }
}
