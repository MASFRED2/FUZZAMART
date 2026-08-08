<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::withCount('stok_masuk')->orderBy('nama_pemasok')->get();
        return view('admin.pemasok.index', compact('pemasok'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemasok' => ['required', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
        ]);

        $pemasok = Pemasok::create($validated);
        AuditLog::catat('Pemasok', 'Tambah', 'Menambah pemasok ' . $pemasok->nama_pemasok);

        return redirect()->back()->with('success', 'Data pemasok berhasil disimpan.');
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $validated = $request->validate([
            'nama_pemasok' => ['required', 'string', 'max:255'],
            'kontak' => ['nullable', 'string', 'max:50'],
        ]);

        $pemasok->update($validated);
        AuditLog::catat('Pemasok', 'Ubah', 'Mengubah pemasok ' . $pemasok->nama_pemasok);

        return redirect()->back()->with('success', 'Data pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        if ($pemasok->stok_masuk()->exists()) {
            return redirect()->back()->with('error', 'Pemasok tidak bisa dihapus karena sudah digunakan pada data stok masuk.');
        }

        $pemasok->delete();
        AuditLog::catat('Pemasok', 'Hapus', 'Menghapus pemasok.');

        return redirect()->back()->with('success', 'Data pemasok berhasil dihapus.');
    }
}
