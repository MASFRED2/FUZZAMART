<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemasok extends Model
{
    // Mengunci nama tabel bahasa Indonesia
    protected $table = 'pemasok';

    protected $fillable = ['nama_pemasok', 'kontak'];

    // Relasi ke riwayat stok masuk dari pemasok ini
    public function stok_masuk(): HasMany
    {
        return $this->hasMany(StokMasuk::class, 'pemasok_id');
    }
}