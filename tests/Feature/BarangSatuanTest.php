<?php

namespace Tests\Feature;

;
use App\Models\Barang;
use App\Models\BarangSatuan;
use App\Models\Cabang;
use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Satuan;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarangSatuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_membuat_barang_dengan_harga_kemasan_independen(): void
    {
        [$admin, $kategori, $pcs, $dus] = $this->buatMasterData('admin');

        $response = $this->actingAs($admin)->post(route('barang.store'), [
            'kategori_id' => $kategori->id,
            'barcode' => 'PCS-001',
            'nama_barang' => 'Mi Instan Uji',
            'satuan_dasar_id' => $pcs->id,
            'harga_jual' => 3500,
            'harga_beli_terakhir' => 2800,
            'stok_minimal' => 10,
            'kemasan' => [
                ['satuan_id' => $dus->id, 'barcode' => 'DUS-001', 'konversi_satuan' => 40, 'harga_jual' => 125000],
            ],
        ]);

        $response->assertRedirect(route('barang.index'));
        $barang = Barang::where('barcode', 'PCS-001')->firstOrFail();

        $this->assertDatabaseHas('barang_satuan', [
            'barang_id' => $barang->id,
            'satuan_id' => $pcs->id,
            'konversi_satuan' => 1,
            'harga_jual' => 3500,
            'is_default' => true,
        ]);
        $this->assertDatabaseHas('barang_satuan', [
            'barang_id' => $barang->id,
            'satuan_id' => $dus->id,
            'barcode' => 'DUS-001',
            'konversi_satuan' => 40,
            'harga_jual' => 125000,
        ]);
    }

    public function test_stok_masuk_dalam_dus_dikonversi_ke_satuan_dasar(): void
    {
        [$admin, $kategori, $pcs, $dus] = $this->buatMasterData('admin');
        [$barang, $kemasanDus] = $this->buatBarangDenganDus($kategori, $pcs, $dus, $admin->cabang_id);
        $pemasok = Pemasok::create(['nama_pemasok' => 'Pemasok Uji', 'kontak' => '0800000000']);

        $response = $this->actingAs($admin)->post(route('stok-masuk.store'), [
            'barang_satuan_id' => $kemasanDus->id,
            'pemasok_id' => $pemasok->id,
            'jumlah_kemasan' => 2,
            'harga_beli_kemasan' => 100000,
        ]);

        $response->assertRedirect();
        $this->assertSame(80, $barang->refresh()->stok_total);
        $this->assertDatabaseHas('stok_masuk', [
            'barang_id' => $barang->id,
            'barang_satuan_id' => $kemasanDus->id,
            'jumlah_kemasan' => 2,
            'jumlah_masuk' => 80,
            'konversi_satuan' => 40,
        ]);
    }

    public function test_pos_menjual_dus_dengan_harga_dus_dan_mengurangi_stok_dasar(): void
    {
        [$kasir, $kategori, $pcs, $dus] = $this->buatMasterData('kasir');
        [$barang, $kemasanDus] = $this->buatBarangDenganDus($kategori, $pcs, $dus, $kasir->cabang_id, 100);
        Shift::create([
            'user_id' => $kasir->id,
            'cabang_id' => $kasir->cabang_id,
            'waktu_buka' => now(),
            'saldo_awal' => 100000,
        ]);

        $this->actingAs($kasir)
            ->get(route('kasir.get-barang', 'DUS-001'))
            ->assertOk()
            ->assertJsonPath('data.barang_satuan_id', $kemasanDus->id)
            ->assertJsonPath('data.satuan', 'dus')
            ->assertJsonPath('data.harga_jual', 125000);

        $response = $this->actingAs($kasir)->postJson(route('penjualan.store'), [
            'keranjang' => [[
                'id' => $barang->id,
                'barang_satuan_id' => $kemasanDus->id,
                'qty' => 2,
            ]],
            'metode_pembayaran' => 'cash',
            'total_bayar' => 250000,
        ]);

        $response->assertOk()->assertJsonPath('status', 'success');
        $this->assertSame(20, $barang->refresh()->stok_total);
        $this->assertDatabaseHas('penjualan_detail', [
            'barang_id' => $barang->id,
            'barang_satuan_id' => $kemasanDus->id,
            'qty' => 80,
            'qty_jual' => 2,
            'satuan_jual' => 'dus',
            'konversi_satuan' => 40,
            'harga_satuan' => 125000,
            'subtotal' => 250000,
        ]);
    }

    private function buatMasterData(string $role): array
{
    $cabang = Cabang::create([
        'nama_cabang' => 'Lokasi Uji',
        'alamat' => 'Alamat Uji',
    ]);

    $user = User::factory()->create([
    'role' => $role,
    'cabang_id' => $cabang->id,
]);

    $kategori = Kategori::create([
        'nama_kategori' => 'Makanan Uji',
    ]);

    $pcs = Satuan::where('simbol', 'pcs')->firstOrFail();
    $dus = Satuan::where('simbol', 'dus')->firstOrFail();

    return [$user, $kategori, $pcs, $dus];
}

    private function buatBarangDenganDus(Kategori $kategori, Satuan $pcs, Satuan $dus, int $cabangId, int $stok = 0): array
    {
        $barang = Barang::create([
            'kategori_id' => $kategori->id,
            'cabang_id' => $cabangId,
            'barcode' => 'PCS-001',
            'nama_barang' => 'Mi Instan Uji',
            'satuan' => 'pcs',
            'stok_total' => $stok,
            'stok_minimal' => 10,
            'harga_jual' => 3500,
            'harga_beli_terakhir' => 2500,
            'is_active' => true,
        ]);

        BarangSatuan::create([
            'barang_id' => $barang->id,
            'satuan_id' => $pcs->id,
            'barcode' => 'PCS-001',
            'konversi_satuan' => 1,
            'harga_jual' => 3500,
            'is_default' => true,
            'is_active' => true,
        ]);
        $kemasanDus = BarangSatuan::create([
            'barang_id' => $barang->id,
            'satuan_id' => $dus->id,
            'barcode' => 'DUS-001',
            'konversi_satuan' => 40,
            'harga_jual' => 125000,
            'is_default' => false,
            'is_active' => true,
        ]);

        return [$barang, $kemasanDus];
    }
}
