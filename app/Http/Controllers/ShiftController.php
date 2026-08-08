<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Penjualan;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $shiftAktif = Shift::where('user_id', Auth::id())
            ->whereNull('waktu_tutup')
            ->first();

        $totalPenjualanCash = 0;
        $totalPenjualanNonCash = 0;
        $estimasiKas = 0;

        if ($shiftAktif) {
            $base = Penjualan::where('kasir_id', Auth::id())
                ->where('created_at', '>=', $shiftAktif->waktu_buka)
                ->where('status_pembayaran', 'sukses');

            $totalPenjualanCash = (clone $base)->where('metode_pembayaran', 'cash')->sum('total_harga');
            $totalPenjualanNonCash = (clone $base)->where('metode_pembayaran', '!=', 'cash')->sum('total_harga');
            $estimasiKas = (float) $shiftAktif->saldo_awal + (float) $totalPenjualanCash;
        }

        $riwayatShift = Shift::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('admin.shift.index', compact('shiftAktif', 'totalPenjualanCash', 'totalPenjualanNonCash', 'estimasiKas', 'riwayatShift'));
    }

    public function bukaShift(Request $request)
    {
        $validated = $request->validate([
            'saldo_awal' => ['required', 'numeric', 'min:0'],
        ]);

        $cek = Shift::where('user_id', Auth::id())->whereNull('waktu_tutup')->exists();
        if ($cek) {
            return redirect()->back()->with('error', 'Anda masih memiliki shift yang aktif. Tutup shift terlebih dahulu.');
        }

        Shift::create([
            'user_id' => Auth::id(),
            'cabang_id' => Auth::user()->cabang_id,
            'waktu_buka' => now(),
            'saldo_awal' => $validated['saldo_awal'],
        ]);

        AuditLog::catat('Shift', 'Buka', 'Membuka shift dengan saldo awal Rp ' . number_format($validated['saldo_awal'], 0, ',', '.'));

        return redirect()->route('penjualan.index')->with('success', 'Shift berhasil dibuka. Kasir sudah siap digunakan.');
    }

    public function tutupShift(Request $request)
    {
        $validated = $request->validate([
            'saldo_akhir' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $shiftAktif = Shift::where('user_id', Auth::id())->whereNull('waktu_tutup')->first();
        if (!$shiftAktif) {
            return redirect()->back()->with('error', 'Tidak ada shift aktif yang ditemukan.');
        }

        $shiftAktif->update([
            'waktu_tutup' => now(),
            'saldo_akhir' => $validated['saldo_akhir'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        AuditLog::catat('Shift', 'Tutup', 'Menutup shift dengan saldo akhir Rp ' . number_format($validated['saldo_akhir'], 0, ',', '.'));

        return redirect()->route('shift.index')->with('success', 'Shift berhasil ditutup.');
    }
}
