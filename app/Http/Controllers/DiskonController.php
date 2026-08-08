<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    public function index()
    {
        $diskon = Diskon::with('barang')->latest()->get();
        $barang = Barang::aktif()->orderBy('nama_barang')->get();
        return view('admin.diskon.index', compact('diskon', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barang,id'],
            'jenis_diskon' => ['required', 'in:persentase,nominal'],
            'nilai_diskon' => ['required', 'numeric', 'min:0'],
            'minimal_beli' => ['nullable', 'integer', 'min:1'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'status_aktif' => ['nullable', 'boolean'],
        ]);

        $validated['minimal_beli'] = $validated['minimal_beli'] ?? 1;
        $validated['status_aktif'] = $request->boolean('status_aktif', true);
        $diskon = Diskon::create($validated);

        AuditLog::catat('Diskon', 'Tambah', 'Menambah diskon untuk barang ID ' . $diskon->barang_id);

        return redirect()->back()->with('success', 'Diskon berhasil dijadwalkan.');
    }

    public function destroy(Diskon $diskon)
    {
        $diskon->delete();
        AuditLog::catat('Diskon', 'Hapus', 'Menghapus diskon ID ' . $diskon->id);

        return redirect()->back()->with('success', 'Diskon berhasil dihapus.');
    }

    public function daftarPromo()
    {
        $promo = Diskon::with('barang')
            ->where('status_aktif', true)
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai', '>=', now())
            ->get();

        return view('customer.promo', compact('promo'));
    }
}
