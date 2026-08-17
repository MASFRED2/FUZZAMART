<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan', 50);
            $table->string('simbol', 20)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('barang_satuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->foreignId('satuan_id')->constrained('satuan')->restrictOnDelete();
            $table->string('barcode', 100)->nullable()->unique();
            $table->unsignedInteger('konversi_satuan')->default(1);
            $table->decimal('harga_jual', 15, 2);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['barang_id', 'satuan_id']);
            $table->index(['barang_id', 'is_active']);
        });

        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->foreignId('barang_satuan_id')->nullable()->after('barang_id')->constrained('barang_satuan')->nullOnDelete();
            $table->unsignedInteger('qty_jual')->nullable()->after('qty');
            $table->string('satuan_jual', 20)->nullable()->after('qty_jual');
            $table->unsignedInteger('konversi_satuan')->default(1)->after('satuan_jual');
        });

        Schema::table('stok_masuk', function (Blueprint $table) {
            $table->foreignId('barang_satuan_id')->nullable()->after('barang_id')->constrained('barang_satuan')->nullOnDelete();
            $table->unsignedInteger('jumlah_kemasan')->nullable()->after('jumlah_sisa');
            $table->string('satuan_masuk', 20)->nullable()->after('jumlah_kemasan');
            $table->unsignedInteger('konversi_satuan')->default(1)->after('satuan_masuk');
            $table->decimal('harga_beli_kemasan', 15, 2)->nullable()->after('harga_beli');
        });

        $sekarang = now();
        $satuanUmum = [
            ['nama_satuan' => 'Pieces', 'simbol' => 'pcs'],
            ['nama_satuan' => 'Pak', 'simbol' => 'pak'],
            ['nama_satuan' => 'Renteng', 'simbol' => 'renteng'],
            ['nama_satuan' => 'Dus', 'simbol' => 'dus'],
            ['nama_satuan' => 'Botol', 'simbol' => 'botol'],
            ['nama_satuan' => 'Sachet', 'simbol' => 'sachet'],
            ['nama_satuan' => 'Liter', 'simbol' => 'liter'],
            ['nama_satuan' => 'Mililiter', 'simbol' => 'ml'],
            ['nama_satuan' => 'Kilogram', 'simbol' => 'kg'],
            ['nama_satuan' => 'Gram', 'simbol' => 'gram'],
        ];

        foreach ($satuanUmum as $item) {
            DB::table('satuan')->insertOrIgnore([
                ...$item,
                'is_active' => true,
                'created_at' => $sekarang,
                'updated_at' => $sekarang,
            ]);
        }

        foreach (DB::table('barang')->orderBy('id')->get() as $barang) {
            $simbol = strtolower(trim((string) ($barang->satuan ?: 'pcs')));
            $satuanId = DB::table('satuan')->where('simbol', $simbol)->value('id');

            if (!$satuanId) {
                $satuanId = DB::table('satuan')->insertGetId([
                    'nama_satuan' => ucfirst($simbol),
                    'simbol' => $simbol,
                    'is_active' => true,
                    'created_at' => $sekarang,
                    'updated_at' => $sekarang,
                ]);
            }

            DB::table('barang_satuan')->insertOrIgnore([
                'barang_id' => $barang->id,
                'satuan_id' => $satuanId,
                'barcode' => $barang->barcode,
                'konversi_satuan' => 1,
                'harga_jual' => $barang->harga_jual,
                'is_default' => true,
                'is_active' => true,
                'created_at' => $sekarang,
                'updated_at' => $sekarang,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('stok_masuk', function (Blueprint $table) {
            $table->dropConstrainedForeignId('barang_satuan_id');
            $table->dropColumn(['jumlah_kemasan', 'satuan_masuk', 'konversi_satuan', 'harga_beli_kemasan']);
        });

        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropConstrainedForeignId('barang_satuan_id');
            $table->dropColumn(['qty_jual', 'satuan_jual', 'konversi_satuan']);
        });

        Schema::dropIfExists('barang_satuan');
        Schema::dropIfExists('satuan');
    }
};
