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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
        $table->foreignId('kategori_id')->constrained('kategori');
        $table->string('barcode')->unique();
        $table->string('nama_barang');
        $table->integer('stok_total')->default(0);
        $table->integer('stok_minimal')->default(5); // Untuk peringatan stok habis
        $table->decimal('harga_jual', 15, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
