<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    // Mengunci nama tabel bahasa Indonesia
    protected $table = 'cabang';

    protected $fillable = ['nama_cabang', 'alamat'];

    // Relasi ke user (Kasir/Admin yang ditugaskan di cabang ini)
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    // Relasi ke data barang milik cabang ini
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'cabang_id');
    }
}