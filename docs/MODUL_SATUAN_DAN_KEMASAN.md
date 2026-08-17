# Modul Satuan dan Kemasan Barang

Modul ini menyimpan stok setiap barang dalam satuan dasar, lalu menyediakan satu atau lebih kemasan jual dengan barcode, konversi, dan harga jual independen.

Contoh:

| Satuan | Konversi | Harga jual |
| --- | ---: | ---: |
| pcs | 1 | Rp3.500 |
| renteng | 10 pcs | Rp33.000 |
| dus | 40 pcs | Rp125.000 |

Harga kemasan tidak dihitung otomatis. Admin menetapkan harga setiap kemasan secara langsung.

## Menjalankan perubahan

```bash
php artisan migrate
php artisan optimize:clear
```

Untuk instalasi baru dengan data contoh:

```bash
php artisan migrate:fresh --seed
```

Perintah `migrate:fresh` menghapus seluruh data lama. Gunakan hanya pada database pengembangan atau setelah membuat cadangan.

## Alur penggunaan

1. Admin membuka **Satuan & Kemasan** untuk mengelola daftar satuan.
2. Admin membuka **Data Barang**, memilih satuan dasar, lalu menambahkan kemasan tambahan.
3. Stok awal atau stok baru dicatat melalui **Stok Masuk** dengan memilih kemasan penerimaan.
4. Kasir memindai barcode kemasan. POS memakai harga kemasan tersebut dan mengurangi stok dalam satuan dasar.

## Aturan penting

- Satu produk harus mempunyai tepat satu satuan dasar dengan faktor konversi `1`.
- Kemasan tambahan memiliki faktor konversi minimal `2`.
- Barcode harus unik di seluruh barang dan kemasan.
- Satuan dasar tidak dapat diganti setelah barang memiliki stok atau riwayat transaksi.
- Penghapusan kemasan dari form akan menonaktifkannya agar riwayat transaksi lama tetap dapat dibaca.

## Pengujian

```bash
php artisan test --filter=BarangSatuanTest
```

Pengujian mencakup pembuatan harga kemasan independen, penerimaan stok dalam dus, pemindaian barcode dus, serta pengurangan stok dasar saat penjualan.
