<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanDetail extends Model
{
    protected $table = 'penjualan_detail';
    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'barang_satuan_id',
        'qty',
        'qty_jual',
        'satuan_jual',
        'konversi_satuan',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'qty_jual' => 'integer',
        'konversi_satuan' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }

    public function barangSatuan(): BelongsTo
    {
        return $this->belongsTo(BarangSatuan::class, 'barang_satuan_id');
    }

    public function getJumlahTampilAttribute(): int
    {
        return (int) ($this->qty_jual ?: $this->qty);
    }

    public function getSatuanTampilAttribute(): string
    {
        return $this->satuan_jual ?: ($this->barang?->satuan ?? 'pcs');
    }
}
