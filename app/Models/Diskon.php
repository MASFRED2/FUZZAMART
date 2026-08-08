<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diskon extends Model
{
    protected $table = 'diskon';
    protected $fillable = [
        'barang_id', 'jenis_diskon', 'nilai_diskon', 
        'minimal_beli', 'tgl_mulai', 'tgl_selesai', 'status_aktif'
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}