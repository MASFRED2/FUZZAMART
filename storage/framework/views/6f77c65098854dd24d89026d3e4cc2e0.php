<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuzza Mart POS</title>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
    <style>
        body{font-family:"Segoe UI",Arial,sans-serif;background:#0f172a;color:#fff;overflow-x:hidden;}
        .hero{min-height:100vh;background:radial-gradient(circle at 20% 20%,rgba(182,147,119,.28),transparent 32%),linear-gradient(135deg,#0f172a,#1e293b 55%,#111827);display:flex;align-items:center;}
        .glass{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(14px);border-radius:28px;box-shadow:0 30px 80px rgba(0,0,0,.25);}
        .btn-gold{background:#b69377;border-color:#b69377;color:#fff;border-radius:14px;font-weight:800;padding:.85rem 1.3rem;}
        .btn-outline-light{border-radius:14px;font-weight:800;padding:.85rem 1.3rem;}
        .feature{background:#fff;color:#111827;border-radius:20px;padding:22px;height:100%;box-shadow:0 16px 35px rgba(15,23,42,.14)}
        .feature i{font-size:2rem;color:#b69377;margin-bottom:12px}
        .mockup{border-radius:22px;background:#f8fafc;color:#111827;padding:18px;box-shadow:0 30px 80px rgba(0,0,0,.35)}
        .bar{height:10px;border-radius:50px;background:#e5e7eb}.bar.gold{background:#b69377}.bar.green{background:#22c55e}.bar.red{background:#ef4444}
        @media(max-width:768px){.display-3{font-size:2.3rem}.hero{padding:40px 0}}
    </style>
</head>
<body>
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge badge-light text-dark px-3 py-2 mb-3">Sistem POS Berbasis Web</span>
                <h1 class="display-3 font-weight-bold mb-3">Kelola kasir, stok, dan laporan toko lebih cepat.</h1>
                <p class="lead text-light mb-4">Fuzza Mart POS membantu transaksi penjualan, scan barcode, stok masuk, stock opname, retur, laporan stok rendah, dan laporan penjualan dalam satu sistem yang responsif.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-gold mr-2 mb-2"><i class="fas fa-sign-in-alt mr-2"></i>Masuk Sistem</a>
                    <a href="#fitur" class="btn btn-outline-light mb-2"><i class="fas fa-layer-group mr-2"></i>Lihat Fitur</a>
                </div>
                <p class="mt-3 text-muted">Akun demo: admin@gmail.com / admin@123 · kasir@gmail.com / kasir@456</p>
            </div>
            <div class="col-lg-6">
                <div class="mockup">
                    <div class="d-flex justify-content-between mb-4"><strong>Dashboard Fuzza Mart</strong><span class="badge badge-success">Online</span></div>
                    <div class="row">
                        <div class="col-6 mb-3"><div class="glass p-3" style="background:#0f172a;color:white"><small>Penjualan Hari Ini</small><h3>Rp 2.450.000</h3></div></div>
                        <div class="col-6 mb-3"><div class="glass p-3" style="background:#b69377;color:white"><small>Transaksi</small><h3>128</h3></div></div>
                    </div>
                    <div class="p-3 bg-white rounded mb-3"><div class="d-flex justify-content-between"><span>Indomie Goreng</span><strong>Rp 3.500</strong></div><div class="bar gold mt-2" style="width:80%"></div></div>
                    <div class="p-3 bg-white rounded mb-3"><div class="d-flex justify-content-between"><span>Teh Botol</span><strong>Rp 5.000</strong></div><div class="bar green mt-2" style="width:60%"></div></div>
                    <div class="p-3 bg-white rounded"><div class="d-flex justify-content-between"><span>Stok Rendah</span><strong>7 Barang</strong></div><div class="bar red mt-2" style="width:35%"></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="fitur" class="py-5 bg-light text-dark">
    <div class="container py-4">
        <div class="text-center mb-5"><h2 class="font-weight-bold">Fitur Utama</h2><p class="text-muted">Dibuat untuk kebutuhan toko ritel yang membutuhkan transaksi cepat dan stok akurat.</p></div>
        <div class="row">
            <div class="col-md-4 mb-4"><div class="feature"><i class="fas fa-barcode"></i><h5 class="font-weight-bold">Kasir Barcode</h5><p>Scan barcode, keranjang otomatis, validasi stok, hitung total dan kembalian.</p></div></div>
            <div class="col-md-4 mb-4"><div class="feature"><i class="fas fa-boxes"></i><h5 class="font-weight-bold">Manajemen Stok</h5><p>Stok masuk, kartu stok, stock opname, retur, dan notifikasi stok rendah.</p></div></div>
            <div class="col-md-4 mb-4"><div class="feature"><i class="fas fa-chart-line"></i><h5 class="font-weight-bold">Laporan Owner</h5><p>Laporan penjualan, barang terlaris, slow moving, expired, dan shift kasir.</p></div></div>
        </div>
    </div>
</section>
</body>
</html>
<?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/welcome.blade.php ENDPATH**/ ?>