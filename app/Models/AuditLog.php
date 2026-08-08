<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'modul',
        'aksi',
        'deskripsi',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function catat(string $modul, string $aksi, ?string $deskripsi = null): void
    {
        try {
            static::create([
                'user_id' => auth()->id(),
                'modul' => $modul,
                'aksi' => $aksi,
                'deskripsi' => $deskripsi,
                'ip_address' => request()?->ip(),
                'user_agent' => substr((string) request()?->userAgent(), 0, 500),
            ]);
        } catch (\Throwable $e) {
            // Audit log tidak boleh membuat proses utama gagal.
        }
    }
}
