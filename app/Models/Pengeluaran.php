<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    // Mengunci nama tabel bahasa Indonesia sesuai database migration
    protected $table = 'pengeluaran';

    protected $fillable = [
        'cabang_id',
        'nama_pengeluaran',
        'nominal',
        'tgl_pengeluaran'
    ];

    // Otomatis mengubah string tanggal menjadi objek Carbon agar mudah diformat di view
    protected $casts = [
        'tgl_pengeluaran' => 'date'
    ];

    // Relasi: Setiap 1 catatan pengeluaran terikat pada 1 lokasi Cabang tertentu
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}