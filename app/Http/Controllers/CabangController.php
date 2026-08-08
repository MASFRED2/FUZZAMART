<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = Cabang::withCount(['users', 'barang'])->orderBy('nama_cabang')->get();
        return view('admin.cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        $cabang = Cabang::create($validated);
        AuditLog::catat('Cabang', 'Tambah', 'Menambah cabang ' . $cabang->nama_cabang);

        return redirect()->back()->with('success', 'Cabang berhasil didaftarkan.');
    }

    public function update(Request $request, Cabang $cabang)
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        $cabang->update($validated);
        AuditLog::catat('Cabang', 'Ubah', 'Mengubah cabang ' . $cabang->nama_cabang);

        return redirect()->back()->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        if ($cabang->users()->exists() || $cabang->barang()->exists()) {
            return redirect()->back()->with('error', 'Cabang tidak bisa dihapus karena masih memiliki user atau barang.');
        }

        $cabang->delete();
        AuditLog::catat('Cabang', 'Hapus', 'Menghapus cabang.');

        return redirect()->back()->with('success', 'Cabang berhasil dihapus.');
    }
}
