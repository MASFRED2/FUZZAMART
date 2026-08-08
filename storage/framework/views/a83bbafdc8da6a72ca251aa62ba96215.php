<?php $__env->startSection('judul', 'Dashboard Operasional'); ?>

<?php $__env->startSection('isi'); ?>
<div class="row">
    <div class="col-lg-3 col-6"><div class="small-box bg-success"><div class="inner"><h3>Rp <?php echo e(number_format($totalPenjualanHariIni,0,',','.')); ?></h3><p>Penjualan Hari Ini</p></div><div class="icon"><i class="fas fa-money-bill-wave"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-info"><div class="inner"><h3><?php echo e($jumlahTransaksiHariIni); ?></h3><p>Transaksi Hari Ini</p></div><div class="icon"><i class="fas fa-receipt"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3><?php echo e($stokRendah); ?></h3><p>Barang Stok Rendah</p></div><div class="icon"><i class="fas fa-exclamation-triangle"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3><?php echo e($expiredDekat); ?></h3><p>Barang Mendekati Expired</p></div><div class="icon"><i class="fas fa-calendar-times"></i></div></div></div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-clock mr-2"></i>Transaksi Terbaru</h5>
                <a href="<?php echo e(route('penjualan.riwayat')); ?>" class="btn btn-sm btn-dark">Lihat Semua</a>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Invoice</th><th>Kasir</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="<?php echo e(route('penjualan.cetak',$trx->id)); ?>" target="_blank" class="font-weight-bold"><?php echo e($trx->no_invoice); ?></a></td>
                                <td><?php echo e($trx->kasir?->name ?? '-'); ?></td>
                                <td>Rp <?php echo e(number_format($trx->total_harga,0,',','.')); ?></td>
                                <td><span class="badge badge-<?php echo e($trx->status_pembayaran === 'retur' ? 'danger' : 'success'); ?>"><?php echo e(strtoupper($trx->status_pembayaran)); ?></span></td>
                                <td><?php echo e($trx->created_at->format('d/m/Y H:i')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold"><i class="fas fa-box-open mr-2"></i>Stok Perlu Perhatian</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Barang</th><th>Stok</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $barangKritis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($b->nama_barang); ?><br><small class="text-muted">Min: <?php echo e($b->stok_minimal); ?></small></td>
                                <td><span class="badge badge-<?php echo e($b->stok_total <= 0 ? 'danger' : 'warning'); ?>"><?php echo e($b->stok_total); ?></span></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="2" class="text-center text-muted py-4">Stok aman.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5 class="mb-0 font-weight-bold"><i class="fas fa-user-clock mr-2"></i>Shift Aktif</h5></div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $shiftAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span><?php echo e($s->user?->name); ?></span><small><?php echo e($s->waktu_buka->format('H:i')); ?></small>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">Belum ada shift aktif.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/dashboard.blade.php ENDPATH**/ ?>