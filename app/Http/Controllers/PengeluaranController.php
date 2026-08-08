<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Cabang;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        // Mengambil semua data pengeluaran beserta info cabangnya
        $pengeluaran = Pengeluaran::with('cabang')->latest()->get();
        // Mengambil data cabang untuk pilihan dropdown di modal form
        $cabang = Cabang::all();
        
        return view('admin.pengeluaran.index', compact('pengeluaran', 'cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
        ]);

        Pengeluaran::create($request->all());

        return redirect()->back()->with('success', 'Biaya pengeluaran operasional berhasil dicatat!');
    }

    public function destroy($id)
    {
        Pengeluaran::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}