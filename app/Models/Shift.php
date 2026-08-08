<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shift extends Model
{
    // 1. Mengunci nama tabel dalam bahasa Indonesia sesuai database migration v2
    protected $table = 'shift';

    // 2. Mendaftarkan seluruh kolom yang wajib diisi demi keamanan finansial laci kasir
    protected $fillable = [
        'user_id',
        'cabang_id',
        'waktu_buka',
        'waktu_tutup',
        'saldo_awal',
        'saldo_akhir',
        'catatan'
    ];

    // 3. Casting tipe data: Mengubah string datetime dari database menjadi objek Carbon secara otomatis.
    // Ini sangat penting agar kita bisa memanipulasi waktu (format tanggal, pencarian durasi shift) di controller/view.
    protected $casts = [
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
    ];

    /**
     * Relasi Akuntansi Internal POS:
     * Menghubungkan data shift dengan aktor/staff yang bertanggung jawab.
     */
    
    // Setiap 1 data catatan shift hanya dimiliki oleh 1 orang User (Kasir/Admin)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Setiap 1 data catatan shift terikat pada 1 Lokasi Cabang tempat laci kas tersebut berada
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}