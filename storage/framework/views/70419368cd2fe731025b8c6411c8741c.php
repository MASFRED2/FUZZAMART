<?php $__env->startSection('judul', 'Analitik Produk (Slow Moving)'); ?>

<?php $__env->startSection('isi'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">10 Barang Paling Jarang Dibeli</h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">*Data berdasarkan frekuensi munculnya barang dalam struk belanja.</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Total Kali Dibeli</th>
                            <th>Stok Saat Ini</th>
                            <th>Saran Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $slowMoving; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($b->nama_barang); ?></td>
                            <td><?php echo e($b->penjualan_detail_count); ?> kali</td>
                            <td><?php echo e($b->stok_total); ?></td>
                            <td>
                                <?php if($b->penjualan_detail_count == 0): ?>
                                    <span class="text-danger">Berikan Diskon Besar / Hentikan Stok</span>
                                <?php else: ?>
                                    <span class="text-warning">Promosikan di Dashboard Customer</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/admin/laporan/analitik.blade.php ENDPATH**/ ?>