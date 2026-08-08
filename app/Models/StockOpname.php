<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpname extends Model
{
    // Mengunci nama tabel sesuai database migration v2
    protected $table = 'stock_opname';

    protected $fillable = [
        'barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan'
    ];

    // Relasi: Setiap 1 catatan stock opname merujuk pada 1 barang tertentu
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id')->withTrashed();
    }
}