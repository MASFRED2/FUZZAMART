<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::withCount('barangSatuan')->orderBy('nama_satuan')->get();

        return view('admin.satuan.index', compact('satuan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_satuan' => ['required', 'string', 'max:50'],
            'simbol' => ['required', 'string', 'max:20', 'unique:satuan,simbol'],
        ]);

        $validated['simbol'] = strtolower(trim($validated['simbol']));
        $validated['is_active'] = true;

        $satuan = Satuan::create($validated);
        AuditLog::catat('Satuan Barang', 'Tambah', 'Menambah satuan '.$satuan->nama_satuan);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function update(Request $request, Satuan $satuan)
    {
        $validated = $request->validate([
            'nama_satuan' => ['required', 'string', 'max:50'],
            'simbol' => ['required', 'string', 'max:20', Rule::unique('satuan', 'simbol')->ignore($satuan->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['simbol'] = strtolower(trim($validated['simbol']));
        $validated['is_active'] = $request->boolean('is_active');
        $satuan->update($validated);

        AuditLog::catat('Satuan Barang', 'Ubah', 'Mengubah satuan '.$satuan->nama_satuan);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->barangSatuan()->exists()) {
            return redirect()->route('satuan.index')->with('error', 'Satuan tidak dapat dihapus karena sudah dipakai oleh barang. Nonaktifkan satuan jika tidak ingin dipakai lagi.');
        }

        $nama = $satuan->nama_satuan;
        $satuan->delete();
        AuditLog::catat('Satuan Barang', 'Hapus', 'Menghapus satuan '.$nama);

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
