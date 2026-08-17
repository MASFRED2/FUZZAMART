<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $satuan = [
            ['nama_satuan' => 'Pieces', 'simbol' => 'pcs'],
            ['nama_satuan' => 'Pak', 'simbol' => 'pak'],
            ['nama_satuan' => 'Renteng', 'simbol' => 'renteng'],
            ['nama_satuan' => 'Dus', 'simbol' => 'dus'],
            ['nama_satuan' => 'Botol', 'simbol' => 'botol'],
            ['nama_satuan' => 'Sachet', 'simbol' => 'sachet'],
            ['nama_satuan' => 'Liter', 'simbol' => 'liter'],
            ['nama_satuan' => 'Mililiter', 'simbol' => 'ml'],
            ['nama_satuan' => 'Kilogram', 'simbol' => 'kg'],
            ['nama_satuan' => 'Gram', 'simbol' => 'gram'],
        ];

        foreach ($satuan as $item) {
            Satuan::updateOrCreate(
                ['simbol' => $item['simbol']],
                [...$item, 'is_active' => true]
            );
        }
    }
}
