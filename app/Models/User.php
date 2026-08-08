<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'telepon',
    'cabang_id',
    'poin_loyalitas',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    // Relasi ke transaksi (Jika user adalah Kasir)
    public function penjualan_kasir(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'kasir_id');
    }

    // Relasi ke transaksi (Jika user adalah Pelanggan)
    public function riwayat_belanja(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }
}