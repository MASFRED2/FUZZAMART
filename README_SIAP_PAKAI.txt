FUZZA MART POS - VERSI DIPERBAIKI

Perbaikan utama:
1. Halaman utama sebelum login sudah diganti dari halaman Laravel default menjadi landing page Fuzza Mart POS.
2. Dashboard dibuat dinamis: penjualan hari ini, jumlah transaksi, stok rendah, barang mendekati expired, transaksi terbaru, dan shift aktif.
3. Data barang sudah lebih aman: tambah, edit, hapus/arsip berjalan, validasi barcode unik, filter, pencarian, status aktif/nonaktif, stok minimal, satuan, dan harga beli terakhir.
4. Kategori sudah bisa tambah, edit, hapus dengan proteksi jika masih dipakai barang.
5. POS kasir diperbaiki: scan barcode/nama barang, validasi stok, hitung total/kembalian, metode pembayaran cash/QRIS/debit/transfer, hold transaksi di browser, shortcut keyboard, dan proses bayar via AJAX.
6. Stok masuk sekarang mencatat kartu stok dan memperbarui stok otomatis.
7. Stock opname sekarang mencatat kartu stok dan menyesuaikan stok sistem.
8. Retur penjualan dilindungi agar invoice tidak bisa diretur dua kali, dan stok otomatis dikembalikan.
9. Riwayat transaksi dan laporan penjualan ditambahkan.
10. UI/UX dashboard dan layout dibuat lebih responsif dan rapi.
11. Audit log aktivitas ditambahkan untuk aktivitas penting.

Cara menjalankan dari awal:
1. Import/siapkan database MySQL kosong sesuai nama DB di file .env.
2. Jalankan:
   php artisan migrate:fresh --seed
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   php artisan serve

Akun demo:
Admin: admin@gmail.com / admin@123
Kasir: kasir@gmail.com / kasir@456
Member: member@gmail.com / member123

Catatan penting:
- Karena ada migration baru untuk penguatan sistem, disarankan menggunakan php artisan migrate:fresh --seed agar struktur database benar-benar bersih.
- Data dummy transaksi lama akan hilang jika memakai migrate:fresh.
- Barang yang sudah punya riwayat tidak dihapus permanen, tetapi diarsipkan agar riwayat transaksi tetap aman.
