<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // Cek jika kolom belum ada, maka buat kolomnya
            if (!Schema::hasColumn('penjualan', 'cabang_id')) {
                $table->foreignId('cabang_id')
                      ->nullable()
                      ->after('kasir_id')
                      ->constrained('cabang')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (Schema::hasColumn('penjualan', 'cabang_id')) {
                $table->dropForeign(['cabang_id']);
                $table->dropColumn('cabang_id');
            }
        });
    }
};