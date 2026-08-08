<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Barang;
use App\Models\KartuStok;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $shiftAktif = Shift::where('user_id', Auth::id())
            ->whereNull('waktu_tutup')
            ->first();

        if (!$shiftAktif) {
            return redirect()->route('shift.index')->with('error', 'Anda harus membuka shift terlebih dahulu sebelum melakukan transaksi.');
        }

        $pelanggan = User::where('role', 'pelanggan')->orderBy('name')->get();

        return view('admin.penjualan.index', compact('shiftAktif', 'pelanggan'));
    }

    public function riwayat(Request $request)
    {
        $query = Penjualan::with(['kasir', 'pelanggan', 'detail_penjualan.barang'])->latest();

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('no_invoice', 'like', "%{$q}%")
                    ->orWhereHas('kasir', fn ($u) => $u->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('pelanggan', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        $penjualan = $query->paginate(15)->withQueryString();

        return view('admin.penjualan.riwayat', compact('penjualan'));
    }

    public function getBarang($keyword)
    {
        $keyword = trim(urldecode($keyword));

        $barang = Barang::with('kategori')
            ->aktif()
            ->where(function ($query) use ($keyword) {
                $query->where('barcode', $keyword)
                    ->orWhere('nama_barang', 'like', "%{$keyword}%");
            })
            ->orderByRaw('barcode = ? desc', [$keyword])
            ->first();

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan atau sedang nonaktif.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $barang->id,
                'barcode' => $barang->barcode,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori?->nama_kategori,
                'satuan' => $barang->satuan ?? 'pcs',
                'stok_total' => (int) $barang->stok_total,
                'harga_jual' => (float) $barang->harga_jual,
                'harga_final' => $barang->hargaSetelahDiskon(),
                'ada_diskon' => (bool) $barang->diskon_aktif(),
            ],
        ]);
    }

    public function cetakStruk($id)
    {
        $penjualan = Penjualan::with(['detail_penjualan.barang', 'kasir', 'cabang', 'pelanggan'])->findOrFail($id);
        return view('admin.penjualan.struk', compact('penjualan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'keranjang' => ['required', 'array', 'min:1'],
            'keranjang.*.id' => ['required', 'exists:barang,id'],
            'keranjang.*.qty' => ['required', 'integer', 'min:1'],
            'metode_pembayaran' => ['required', 'in:cash,qris,debit,transfer'],
            'pelanggan_id' => ['nullable', 'exists:users,id'],
            'total_bayar' => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $shiftAktif = Shift::where('user_id', Auth::id())
                    ->whereNull('waktu_tutup')
                    ->lockForUpdate()
                    ->first();

                if (!$shiftAktif) {
                    throw new \Exception('Shift belum dibuka. Silakan buka shift terlebih dahulu.');
                }

                $tanggal = now()->format('Ymd');
                $count = Penjualan::whereDate('created_at', now()->toDateString())->lockForUpdate()->count() + 1;
                $noInvoice = 'POS-' . $tanggal . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

                $items = [];
                $totalHarga = 0;

                foreach ($validated['keranjang'] as $item) {
                    $barang = Barang::where('id', $item['id'])->aktif()->lockForUpdate()->first();

                    if (!$barang) {
                        throw new \Exception('Ada barang yang tidak ditemukan atau sudah nonaktif.');
                    }

                    $qty = (int) $item['qty'];
                    if ($barang->stok_total < $qty) {
                        throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Sisa stok: {$barang->stok_total}.");
                    }

                    $harga = $barang->hargaSetelahDiskon();
                    $subtotal = $harga * $qty;
                    $totalHarga += $subtotal;

                    $items[] = compact('barang', 'qty', 'harga', 'subtotal');
                }

                $totalBayar = $validated['metode_pembayaran'] === 'cash'
                    ? (float) ($validated['total_bayar'] ?? 0)
                    : $totalHarga;

                if ($validated['metode_pembayaran'] === 'cash' && $totalBayar < $totalHarga) {
                    throw new \Exception('Uang tunai yang diterima kurang dari total belanja.');
                }

                $penjualan = Penjualan::create([
                    'no_invoice' => $noInvoice,
                    'kasir_id' => Auth::id(),
                    'cabang_id' => Auth::user()->cabang_id,
                    'shift_id' => $shiftAktif->id,
                    'pelanggan_id' => $validated['pelanggan_id'] ?? null,
                    'total_harga' => $totalHarga,
                    'total_bayar' => $totalBayar,
                    'kembalian' => max(0, $totalBayar - $totalHarga),
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_pembayaran' => 'sukses',
                ]);

                foreach ($items as $item) {
                    PenjualanDetail::create([
                        'penjualan_id' => $penjualan->id,
                        'barang_id' => $item['barang']->id,
                        'qty' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $stokSebelum = $item['barang']->stok_total;
                    $item['barang']->decrement('stok_total', $item['qty']);
                    $item['barang']->refresh();

                    KartuStok::create([
                        'barang_id' => $item['barang']->id,
                        'user_id' => Auth::id(),
                        'tipe' => 'keluar',
                        'referensi' => $penjualan->no_invoice,
                        'qty_masuk' => 0,
                        'qty_keluar' => $item['qty'],
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $item['barang']->stok_total,
                        'keterangan' => 'Penjualan barang melalui POS.',
                    ]);
                }

                if (!empty($validated['pelanggan_id'])) {
                    $poinBaru = (int) floor($totalHarga / 10000);
                    if ($poinBaru > 0) {
                        User::where('id', $validated['pelanggan_id'])->increment('poin_loyalitas', $poinBaru);
                    }
                }

                AuditLog::catat('Transaksi', 'Tambah', 'Menyimpan transaksi ' . $penjualan->no_invoice);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil disimpan.',
                    'id_penjualan' => $penjualan->id,
                    'no_invoice' => $penjualan->no_invoice,
                ]);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
