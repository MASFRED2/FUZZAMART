<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\KartuStok;
use App\Models\Penjualan;
use App\Models\ReturPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        $retur = ReturPenjualan::with('penjualan.kasir')->latest()->get();
        return view('admin.retur.index', compact('retur'));
    }

    public function getInvoice($no_invoice)
    {
        $penjualan = Penjualan::with('detail_penjualan.barang')
            ->where('no_invoice', $no_invoice)
            ->first();

        if (!$penjualan) {
            return response()->json(['status' => 'error', 'message' => 'Nomor invoice tidak ditemukan.'], 404);
        }

        if ($penjualan->status_pembayaran === 'retur') {
            return response()->json(['status' => 'error', 'message' => 'Invoice ini sudah pernah diretur.'], 422);
        }

        return response()->json(['status' => 'success', 'data' => $penjualan]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penjualan_id' => ['required', 'exists:penjualan,id'],
            'alasan' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $penjualan = Penjualan::with('detail_penjualan.barang')
                    ->lockForUpdate()
                    ->findOrFail($validated['penjualan_id']);

                if ($penjualan->status_pembayaran === 'retur') {
                    throw new \Exception('Invoice ini sudah pernah diretur.');
                }

                $retur = ReturPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'alasan' => $validated['alasan'],
                    'total_refund' => $penjualan->total_harga,
                ]);

                foreach ($penjualan->detail_penjualan as $detail) {
                    $barang = Barang::withTrashed()->where('id', $detail->barang_id)->lockForUpdate()->first();
                    if (!$barang) {
                        continue;
                    }

                    $stokSebelum = $barang->stok_total;
                    $barang->increment('stok_total', $detail->qty);
                    $barang->refresh();

                    KartuStok::create([
                        'barang_id' => $barang->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'retur',
                        'referensi' => 'RETUR-' . $retur->id . ' / ' . $penjualan->no_invoice,
                        'qty_masuk' => $detail->qty,
                        'qty_keluar' => 0,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $barang->stok_total,
                        'keterangan' => 'Retur penjualan: ' . $validated['alasan'],
                    ]);
                }

                $penjualan->update(['status_pembayaran' => 'retur']);
                AuditLog::catat('Retur Penjualan', 'Tambah', 'Retur invoice ' . $penjualan->no_invoice);
            });

            return redirect()->back()->with('success', 'Retur berhasil diproses. Stok barang sudah dikembalikan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal memproses retur: ' . $e->getMessage());
        }
    }
}
