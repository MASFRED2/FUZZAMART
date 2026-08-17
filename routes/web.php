<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturPenjualanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StokMasukController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('retur', ReturPenjualanController::class)->only(['index', 'store']);
        Route::get('/ambil-invoice/{no_invoice}', [ReturPenjualanController::class, 'getInvoice'])->name('retur.ambil-invoice');

        Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
        Route::post('/shift/buka', [ShiftController::class, 'bukaShift'])->name('shift.buka');
        Route::post('/shift/tutup', [ShiftController::class, 'tutupShift'])->name('shift.tutup');

        Route::get('/transaksi', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/transaksi/riwayat', [PenjualanController::class, 'riwayat'])->name('penjualan.riwayat');
        Route::get('/kasir/get-barang/{keyword}', [PenjualanController::class, 'getBarang'])->name('kasir.get-barang');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/transaksi/cetak/{id}', [PenjualanController::class, 'cetakStruk'])->name('penjualan.cetak');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('kategori', KategoriController::class)->except(['create', 'show', 'edit']);
        Route::resource('satuan', SatuanController::class)->except(['create', 'show', 'edit']);
        Route::resource('barang', BarangController::class)->except(['create', 'show', 'edit']);
        Route::resource('stok-masuk', StokMasukController::class)->only(['index', 'store']);
        Route::resource('diskon', DiskonController::class)->except(['create', 'show', 'edit', 'update']);
        Route::resource('cabang', CabangController::class)->except(['create', 'show', 'edit']);
        Route::resource('pemasok', PemasokController::class)->except(['create', 'show', 'edit']);
        Route::resource('pelanggan', PelangganController::class)->except(['create', 'show', 'edit', 'update']);
        Route::resource('pengeluaran', PengeluaranController::class)->except(['create', 'show', 'edit', 'update']);
        Route::resource('stock-opname', StockOpnameController::class)->only(['index', 'store']);

        Route::get('/laporan/stok-rendah', [LaporanController::class, 'stokRendah'])->name('laporan.stok-rendah');
        Route::get('/laporan/expired', [LaporanController::class, 'expired'])->name('laporan.expired');
        Route::get('/laporan/analitik', [LaporanController::class, 'analitik'])->name('laporan.analitik');
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    });

    Route::middleware(['role:pelanggan'])->group(function () {
        Route::get('/promo-diskon', [DiskonController::class, 'daftarPromo'])->name('pelanggan.promo');
    });
});

require __DIR__.'/auth.php';
