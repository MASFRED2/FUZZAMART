<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangSatuan extends Model
{
    protected $table = 'barang_satuan';

    protected $fillable = [
        'barang_id',
        'satuan_id',
        'barcode',
        'konversi_satuan',
        'harga_jual',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'konversi_satuan' => 'integer',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function getStokTersediaAttribute(): int
    {
        $konversi = max(1, (int) $this->konversi_satuan);

        return (int) floor(((int) ($this->barang?->stok_total ?? 0)) / $konversi);
    }

    public function hargaSetelahDiskon(int $qty = 1): float
    {
        if (!$this->barang) {
            return (float) $this->harga_jual;
        }

        return $this->barang->hitungHargaSetelahDiskon((float) $this->harga_jual, $qty);
    }
}
