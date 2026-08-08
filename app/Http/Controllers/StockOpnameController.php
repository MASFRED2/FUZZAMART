<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\KartuStok;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOpname::with('barang')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('barang', function ($sub) use ($q) {
                $sub->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            });
        }

        $opname = $query->paginate(15)->withQueryString();
        $barang = Barang::aktif()->orderBy('nama_barang')->get();

        return view('admin.stock_opname.index', compact('opname', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barang,id'],
            'stok_fisik' => ['required', 'integer', 'min:0'],
            'keterangan' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($validated) {
            $barang = Barang::where('id', $validated['barang_id'])->lockForUpdate()->firstOrFail();
            $stokSistem = $barang->stok_total;
            $stokFisik = (int) $validated['stok_fisik'];
            $selisih = $stokFisik - $stokSistem;

            $opname = StockOpname::create([
                'barang_id' => $barang->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $stokFisik,
                'selisih' => $selisih,
                'keterangan' => $validated['keterangan'],
            ]);

            $barang->update(['stok_total' => $stokFisik]);

            KartuStok::create([
                'barang_id' => $barang->id,
                'user_id' => auth()->id(),
                'tipe' => 'opname',
                'referensi' => 'OPNAME-' . $opname->id,
                'qty_masuk' => $selisih > 0 ? $selisih : 0,
                'qty_keluar' => $selisih < 0 ? abs($selisih) : 0,
                'stok_sebelum' => $stokSistem,
                'stok_sesudah' => $stokFisik,
                'keterangan' => $validated['keterangan'],
            ]);

            AuditLog::catat('Stock Opname', 'Proses', 'Stock opname ' . $barang->nama_barang . '. Selisih: ' . $selisih);
        });

        return redirect()->back()->with('success', 'Stock opname berhasil diproses dan stok sistem sudah disesuaikan.');
    }
}
