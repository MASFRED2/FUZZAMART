<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo e(route('dashboard')); ?>" class="brand-link text-center">
        <span class="brand-text">FUZZA MART</span>
        <small class="d-block text-muted" style="font-size:.7rem;letter-spacing:.08em;">POINT OF SALE</small>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image"><i class="fas fa-user-circle fa-2x text-light"></i></div>
            <div class="info">
                <a href="<?php echo e(route('profile.edit')); ?>" class="d-block font-weight-bold"><?php echo e(Auth::user()->name); ?></a>
                <span class="badge badge-light text-dark"><?php echo e(strtoupper(Auth::user()->role)); ?></span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-chart-line"></i><p>Dashboard</p>
                    </a>
                </li>

                <?php if(in_array(Auth::user()->role, ['admin','kasir'])): ?>
                    <li class="nav-header">OPERASIONAL KASIR</li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('shift.index')); ?>" class="nav-link <?php echo e(request()->is('shift*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-cash-register"></i><p>Shift Kasir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('penjualan.index')); ?>" class="nav-link <?php echo e(request()->is('transaksi') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i><p>Kasir POS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('penjualan.riwayat')); ?>" class="nav-link <?php echo e(request()->is('transaksi/riwayat*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-receipt"></i><p>Riwayat Transaksi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo e(route('retur.index')); ?>" class="nav-link <?php echo e(request()->is('retur*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-undo"></i><p>Retur Penjualan</p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(Auth::user()->role == 'admin'): ?>
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item"><a href="<?php echo e(route('barang.index')); ?>" class="nav-link <?php echo e(request()->is('barang*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-box"></i><p>Data Barang</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('kategori.index')); ?>" class="nav-link <?php echo e(request()->is('kategori*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-tags"></i><p>Kategori</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('stok-masuk.index')); ?>" class="nav-link <?php echo e(request()->is('stok-masuk*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-truck-loading"></i><p>Stok Masuk</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('stock-opname.index')); ?>" class="nav-link <?php echo e(request()->is('stock-opname*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-clipboard-check"></i><p>Stock Opname</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('pemasok.index')); ?>" class="nav-link <?php echo e(request()->is('pemasok*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-handshake"></i><p>Pemasok</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('pelanggan.index')); ?>" class="nav-link <?php echo e(request()->is('pelanggan*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-users"></i><p>Member</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('diskon.index')); ?>" class="nav-link <?php echo e(request()->is('diskon*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-percent"></i><p>Diskon</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('cabang.index')); ?>" class="nav-link <?php echo e(request()->is('cabang*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-store"></i><p>Cabang</p></a></li>

                    <li class="nav-header">LAPORAN</li>
                    <?php ($jumlahKritis = \App\Models\Barang::aktif()->whereRaw('stok_total <= stok_minimal')->count()); ?>
                    <li class="nav-item"><a href="<?php echo e(route('laporan.penjualan')); ?>" class="nav-link <?php echo e(request()->is('laporan/penjualan*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-file-invoice-dollar"></i><p>Laporan Penjualan</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('laporan.stok-rendah')); ?>" class="nav-link <?php echo e(request()->is('laporan/stok-rendah*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Stok Rendah <?php if($jumlahKritis>0): ?><span class="badge badge-warning right"><?php echo e($jumlahKritis); ?></span><?php endif; ?></p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('laporan.expired')); ?>" class="nav-link <?php echo e(request()->is('laporan/expired*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-calendar-times"></i><p>Kontrol Expired</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('laporan.analitik')); ?>" class="nav-link <?php echo e(request()->is('laporan/analitik*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-chart-pie"></i><p>Analitik Barang</p></a></li>
                    <li class="nav-item"><a href="<?php echo e(route('pengeluaran.index')); ?>" class="nav-link <?php echo e(request()->is('pengeluaran*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-wallet"></i><p>Pengeluaran</p></a></li>
                <?php endif; ?>

                <?php if(Auth::user()->role == 'pelanggan'): ?>
                    <li class="nav-header">PELANGGAN</li>
                    <li class="nav-item"><a href="<?php echo e(route('pelanggan.promo')); ?>" class="nav-link <?php echo e(request()->is('promo*') ? 'active' : ''); ?>"><i class="nav-icon fas fa-bullhorn"></i><p>Promo Diskon</p></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>