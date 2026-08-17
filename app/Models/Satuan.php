<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    protected $table = 'satuan';

    protected $fillable = ['nama_satuan', 'simbol', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function barangSatuan(): HasMany
    {
        return $this->hasMany(BarangSatuan::class, 'satuan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
