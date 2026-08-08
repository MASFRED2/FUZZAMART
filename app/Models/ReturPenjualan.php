<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturPenjualan extends Model
{
    // Mengunci nama tabel sesuai database migration v2
    protected $table = 'retur_penjualan';

    protected $fillable = [
        'penjualan_id',
        'alasan',
        'total_refund'
    ];

    // Relasi: Setiap 1 data retur merujuk pada 1 transaksi penjualan (Invoice) asli
    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}