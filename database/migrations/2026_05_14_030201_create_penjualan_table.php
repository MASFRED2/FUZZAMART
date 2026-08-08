<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
        $table->foreignId('kasir_id')->constrained('users'); // Relasi ke tabel user (Admin/Kasir)
        $table->foreignId('pelanggan_id')->nullable()->constrained('users'); // Jika pembeli login
        $table->decimal('total_harga', 15, 2);
        $table->decimal('total_bayar', 15, 2);
        $table->decimal('kembalian', 15, 2);
        $table->string('metode_pembayaran'); // Cash, QRIS, Debit
        $table->string('status_pembayaran'); // Sukses, Pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
