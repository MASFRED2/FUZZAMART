<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barang')) {
            Schema::table('barang', function (Blueprint $table) {
                if (!Schema::hasColumn('barang', 'satuan')) {
                    $table->string('satuan', 30)->default('pcs')->after('nama_barang');
                }
                if (!Schema::hasColumn('barang', 'harga_beli_terakhir')) {
                    $table->decimal('harga_beli_terakhir', 15, 2)->default(0)->after('harga_jual');
                }
                if (!Schema::hasColumn('barang', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('harga_beli_terakhir');
                }
                if (!Schema::hasColumn('barang', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        if (Schema::hasTable('penjualan')) {
            Schema::table('penjualan', function (Blueprint $table) {
                if (!Schema::hasColumn('penjualan', 'shift_id')) {
                    $table->foreignId('shift_id')->nullable()->after('cabang_id')->constrained('shift')->nullOnDelete();
                }
            });
        }

        if (!Schema::hasTable('kartu_stok')) {
            Schema::create('kartu_stok', function (Blueprint $table) {
                $table->id();
                $table->foreignId('barang_id')->constrained('barang');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('tipe'); // masuk, keluar, opname, retur, koreksi
                $table->string('referensi')->nullable();
                $table->integer('qty_masuk')->default(0);
                $table->integer('qty_keluar')->default(0);
                $table->integer('stok_sebelum')->default(0);
                $table->integer('stok_sesudah')->default(0);
                $table->text('keterangan')->nullable();
                $table->timestamps();

                $table->index(['barang_id', 'created_at']);
                $table->index('tipe');
            });
        }

        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('modul');
                $table->string('aksi');
                $table->text('deskripsi')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['modul', 'aksi']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('kartu_stok');

        if (Schema::hasTable('penjualan') && Schema::hasColumn('penjualan', 'shift_id')) {
            Schema::table('penjualan', function (Blueprint $table) {
                $table->dropForeign(['shift_id']);
                $table->dropColumn('shift_id');
            });
        }

        if (Schema::hasTable('barang')) {
            Schema::table('barang', function (Blueprint $table) {
                foreach (['satuan', 'harga_beli_terakhir', 'is_active', 'deleted_at'] as $column) {
                    if (Schema::hasColumn('barang', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
