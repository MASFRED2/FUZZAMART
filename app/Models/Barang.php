<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barang';

    protected $fillable = [
        'kategori_id',
        'cabang_id',
        'barcode',
        'nama_barang',
        'satuan',
        'stok_total',
        'stok_minimal',
        'harga_jual',
        'harga_beli_terakhir',
        'is_active',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_beli_terakhir' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function stok_masuk(): HasMany
    {
        return $this->hasMany(StokMasuk::class, 'barang_id');
    }

    public function diskon(): HasMany
    {
        return $this->hasMany(Diskon::class, 'barang_id');
    }

    public function penjualan_detail(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'barang_id');
    }

    public function stock_opname(): HasMany
    {
        return $this->hasMany(StockOpname::class, 'barang_id');
    }

    public function kartu_stok(): HasMany
    {
        return $this->hasMany(KartuStok::class, 'barang_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok_total <= 0) {
            return 'habis';
        }

        if ($this->stok_total <= $this->stok_minimal) {
            return 'rendah';
        }

        return 'aman';
    }

    public function diskon_aktif()
    {
        return $this->diskon()
            ->where('status_aktif', true)
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai', '>=', now())
            ->first();
    }

    public function hargaSetelahDiskon(): float
    {
        $harga = (float) $this->harga_jual;
        $diskon = $this->diskon_aktif();

        if (!$diskon) {
            return $harga;
        }

        if ($diskon->jenis_diskon === 'persentase') {
            return max(0, $harga - ($harga * ((float) $diskon->nilai_diskon / 100)));
        }

        return max(0, $harga - (float) $diskon->nilai_diskon);
    }
}
