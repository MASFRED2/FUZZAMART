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
        'pemasok_id', 
        'jumlah_masuk', 
        'jumlah_sisa', 
        'harga_beli', 
        'tgl_kadaluwarsa'
    ];

    // Pastikan mutator tanggal ini ada untuk formatting di view
    protected $casts = [
        'tgl_kadaluwarsa' => 'date',
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
}