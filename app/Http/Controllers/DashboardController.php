<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\StokMasuk;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = now()->toDateString();
        $user = Auth::user();

        $queryPenjualan = Penjualan::query();
        if ($user->role === 'kasir') {
            $queryPenjualan->where('kasir_id', $user->id);
        }

        $totalPenjualanHariIni = (clone $queryPenjualan)->whereDate('created_at', $hariIni)->sum('total_harga');
        $jumlahTransaksiHariIni = (clone $queryPenjualan)->whereDate('created_at', $hariIni)->count();
        $stokRendah = Barang::aktif()->whereRaw('stok_total <= stok_minimal')->count();
        $barangHabis = Barang::aktif()->where('stok_total', '<=', 0)->count();
        $barangAktif = Barang::aktif()->count();
        $expiredDekat = StokMasuk::whereNotNull('tgl_kadaluwarsa')
            ->whereDate('tgl_kadaluwarsa', '<=', now()->addDays(30))
            ->count();

        $transaksiTerbaru = Penjualan::with(['kasir', 'detail_penjualan.barang'])
            ->latest()
            ->take(7)
            ->get();

        $barangKritis = Barang::with('kategori')
            ->aktif()
            ->whereRaw('stok_total <= stok_minimal')
            ->orderBy('stok_total')
            ->take(7)
            ->get();

        $shiftAktif = Shift::with('user')
            ->whereNull('waktu_tutup')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalPenjualanHariIni',
            'jumlahTransaksiHariIni',
            'stokRendah',
            'barangHabis',
            'barangAktif',
            'expiredDekat',
            'transaksiTerbaru',
            'barangKritis',
            'shiftAktif'
        ));
    }
}
