<?php $__env->startSection('judul', 'Kontrol Barang Expired'); ?>

<?php $__env->startSection('isi'); ?>
<div class="card">
    <div class="card-header bg-danger text-white">
        <h3 class="card-title">Peringatan Expired (30 Hari ke Depan)</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Kode Batch</th>
                    <th>Sisa Stok Batch</th>
                    <th>Tgl Expired</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="<?php echo e($sm->tgl_kadaluwarsa < now() ? 'table-danger' : ''); ?>">
                    <td><?php echo e($sm->barang->nama_barang); ?></td>
                    <td>Batch-<?php echo e($sm->id); ?></td>
                    <td><?php echo e($sm->jumlah_sisa); ?></td>
                    <td><?php echo e($sm->tgl_kadaluwarsa->format('d/m/Y')); ?></td>
                    <td>
                        <?php if($sm->tgl_kadaluwarsa < now()): ?>
                            <span class="badge badge-danger">SUDAH EXPIRED</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Mendekati Expired</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\Fuzzamart\resources\views/admin/laporan/expired.blade.php ENDPATH**/ ?>