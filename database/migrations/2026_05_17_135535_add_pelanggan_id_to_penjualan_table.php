<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // JIKA kolom pelanggan_id BELUM ada di tabel penjualan, barulah buat kolomnya
            if (!Schema::hasColumn('penjualan', 'pelanggan_id')) {
                $table->foreignId('pelanggan_id')
                      ->nullable()
                      ->after('kasir_id')
                      ->constrained('users')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // JIKA kolom pelanggan_id ADA, barulah hapus relasi dan kolomnya
            if (Schema::hasColumn('penjualan', 'pelanggan_id')) {
                $table->dropForeign(['pelanggan_id']);
                $table->dropColumn('pelanggan_id');
            }
        });
    }
};