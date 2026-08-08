<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('barang')->orderBy('nama_kategori')->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategori,nama_kategori'],
        ]);

        $kategori = Kategori::create($validated);
        AuditLog::catat('Kategori', 'Tambah', 'Menambah kategori ' . $kategori->nama_kategori);

        return redirect()->back()->with('success', 'Kategori berhasil ditambah.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id)],
        ]);

        $kategori->update($validated);
        AuditLog::catat('Kategori', 'Ubah', 'Mengubah kategori ' . $kategori->nama_kategori);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barang()->exists()) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh data barang. Pindahkan barang ke kategori lain terlebih dahulu.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();
        AuditLog::catat('Kategori', 'Hapus', 'Menghapus kategori ' . $nama);

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
