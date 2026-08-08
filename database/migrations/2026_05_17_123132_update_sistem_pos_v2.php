<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Cabang
        Schema::create('cabang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cabang');
            $table->text('alamat');
            $table->timestamps();
        });

        // 2. Tabel Pemasok
        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemasok');
            $table->string('kontak');
            $table->timestamps();
        });

        // 3. Tabel Shift
        Schema::create('shift', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->dateTime('waktu_buka');
            $table->dateTime('waktu_tutup')->nullable();
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('saldo_akhir', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Pengeluaran (Dipastikan hanya ada SATU blok di sini)
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->string('nama_pengeluaran');
            $table->decimal('nominal', 15, 2);
            $table->date('tgl_pengeluaran');
            $table->timestamps();
        });

        // 5. Tabel Retur Penjualan
        Schema::create('retur_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->text('alasan');
            $table->decimal('total_refund', 15, 2);
            $table->timestamps();
        });

        // 6. Tabel Stock Opname
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->text('keterangan');
            $table->timestamps();
        });

        // 7. Modifikasi Tambahan Kolom Baru pada Tabel Users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'poin_loyalitas')) {
                $table->integer('poin_loyalitas')->default(0);
            }
            if (!Schema::hasColumn('users', 'cabang_id')) {
                $table->foreignId('cabang_id')->nullable()->constrained('cabang');
            }
        });

        // 8. Modifikasi Tambahan Kolom Baru pada Tabel Barang
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'cabang_id')) {
                $table->foreignId('cabang_id')->nullable()->constrained('cabang');
            }
        });

        // 9. Modifikasi Tambahan Kolom Pemasok pada Tabel Stok Masuk
        Schema::table('stok_masuk', function (Blueprint $table) {
            if (!Schema::hasColumn('stok_masuk', 'pemasok_id')) {
                $table->foreignId('pemasok_id')->nullable()->constrained('pemasok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_masuk', function (Blueprint $table) {
            $table->dropForeign(['pemasok_id']);
            $table->dropColumn('pemasok_id');
        });
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn('cabang_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropColumn(['poin_loyalitas', 'cabang_id']);
        });
        Schema::dropIfExists('stock_opname');
        Schema::dropIfExists('retur_penjualan');
        Schema::dropIfExists('pengeluaran');
        Schema::dropIfExists('shift');
        Schema::dropIfExists('pemasok');
        Schema::dropIfExists('cabang');
    }
};