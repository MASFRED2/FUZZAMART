<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangSatuan;
use App\Models\Cabang;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SatuanSeeder::class);

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
            $produk = Barang::updateOrCreate(
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

            $pcs = Satuan::where('simbol', 'pcs')->firstOrFail();
            BarangSatuan::updateOrCreate(
                ['barang_id' => $produk->id, 'satuan_id' => $pcs->id],
                [
                    'barcode' => $barcode,
                    'konversi_satuan' => 1,
                    'harga_jual' => $jual,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );
        }

        $mie = Barang::where('barcode', '8991002101034')->firstOrFail();
        $renteng = Satuan::where('simbol', 'renteng')->firstOrFail();
        $dus = Satuan::where('simbol', 'dus')->firstOrFail();

        BarangSatuan::updateOrCreate(
            ['barang_id' => $mie->id, 'satuan_id' => $renteng->id],
            ['barcode' => '8991002101134', 'konversi_satuan' => 10, 'harga_jual' => 33000, 'is_default' => false, 'is_active' => true]
        );
        BarangSatuan::updateOrCreate(
            ['barang_id' => $mie->id, 'satuan_id' => $dus->id],
            ['barcode' => '8991002101234', 'konversi_satuan' => 40, 'harga_jual' => 125000, 'is_default' => false, 'is_active' => true]
        );
    }
}
