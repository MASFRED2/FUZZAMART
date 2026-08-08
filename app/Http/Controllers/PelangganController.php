<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        // Mengambil semua user yang memiliki peran sebagai pelanggan
        $pelanggan = User::where('role', 'pelanggan')->latest()->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telepon' => 'required|string|max:20',
        ]);

        // Membuat data pelanggan baru (password default untuk aktivasi akun kelak)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'password' => Hash::make('member123'),
            'role' => 'pelanggan',
            'poin_loyalitas' => 0
        ]);

        return redirect()->back()->with('success', 'Member baru berhasil didaftarkan!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data member berhasil dihapus.');
    }
}