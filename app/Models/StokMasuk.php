<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';

    // Tambahkan pemasok_id di sini
    protected $fillable = [
        'barang_id', 
        'barang_satuan_id',
        'pemasok_id', 
        'jumlah_masuk', 
        'jumlah_sisa', 
        'jumlah_kemasan',
        'satuan_masuk',
        'konversi_satuan',
        'harga_beli',
        'harga_beli_kemasan',
        'tgl_kadaluwarsa'
    ];

    // Pastikan mutator tanggal ini ada untuk formatting di view
    protected $casts = [
        'tgl_kadaluwarsa' => 'date',
        'jumlah_masuk' => 'integer',
        'jumlah_sisa' => 'integer',
        'jumlah_kemasan' => 'integer',
        'konversi_satuan' => 'integer',
        'harga_beli' => 'decimal:2',
        'harga_beli_kemasan' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }

    // Tambahkan relasi ke model Pemasok
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function barangSatuan(): BelongsTo
    {
        return $this->belongsTo(BarangSatuan::class, 'barang_satuan_id');
    }
}
