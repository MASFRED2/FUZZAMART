<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cabangUtama = Cabang::firstOrCreate(
            ['nama_cabang' => 'Fuzza Mart Pusat'],
            ['alamat' => 'Perum Puri Pasundan I RT 01 RW 07 Blok A No.5, Pangadegan, Pasar Kemis, Tangerang']
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Fuzza Mart',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
                'telepon' => '081122334455',
                'cabang_id' => $cabangUtama->id,
                'poin_loyalitas' => 0,
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@gmail.com'],
            [
                'name' => 'Kasir Fuzza Mart',
                'password' => Hash::make('kasir@456'),
                'role' => 'kasir',
                'telepon' => '085566778899',
                'cabang_id' => $cabangUtama->id,
                'poin_loyalitas' => 0,
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@gmail.com'],
            [
                'name' => 'Member Umum',
                'password' => Hash::make('member123'),
                'role' => 'pelanggan',
                'telepon' => '081234567890',
                'cabang_id' => $cabangUtama->id,
                'poin_loyalitas' => 0,
            ]
        );

        $kategoriData = ['Makanan', 'Minuman', 'Kebutuhan Rumah Tangga', 'Personal Care'];
        $kategori = collect($kategoriData)->mapWithKeys(function ($nama) {
            $data = Kategori::firstOrCreate(['nama_kategori' => $nama]);
            return [$nama => $data];
        });

        Pemasok::firstOrCreate(['nama_pemasok' => 'PT. Sumber Retail Nusantara'], ['kontak' => '021-7401234']);
        Pemasok::firstOrCreate(['nama_pemasok' => 'CV. Distributor Harian'], ['kontak' => '0812-9988-7766']);

        $barang = [
            ['8991002101010', 'Air Mineral 600ml', 'Minuman', 3500, 2400, 50, 10],
            ['8991002101027', 'Teh Botol Kotak', 'Minuman', 5000, 3600, 40, 10],
            ['8991002101034', 'Mie Instan Goreng', 'Makanan', 3500, 2800, 60, 15],
            ['8991002101041', 'Biskuit Cokelat', 'Makanan', 8500, 6500, 25, 8],
            ['8991002101058', 'Sabun Mandi', 'Personal Care', 4500, 3200, 30, 8],
            ['8991002101065', 'Deterjen Sachet', 'Kebutuhan Rumah Tangga', 2000, 1300, 80, 20],
        ];

        foreach ($barang as [$barcode, $nama, $kat, $jual, $beli, $stok, $min]) {
            Barang::updateOrCreate(
                ['barcode' => $barcode],
                [
                    'kategori_id' => $kategori[$kat]->id,
                    'cabang_id' => $cabangUtama->id,
                    'nama_barang' => $nama,
                    'satuan' => 'pcs',
                    'stok_total' => $stok,
                    'stok_minimal' => $min,
                    'harga_jual' => $jual,
                    'harga_beli_terakhir' => $beli,
                    'is_active' => true,
                ]
            );
        }
    }
}
