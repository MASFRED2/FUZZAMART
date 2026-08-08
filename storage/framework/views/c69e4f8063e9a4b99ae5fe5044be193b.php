<?php $__env->startSection('judul', 'Manajemen Diskon Barang'); ?>

<?php $__env->startSection('isi'); ?>
<div class="card">
    <div class="card-header">
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDiskon">
            <i class="fas fa-percent"></i> Buat Diskon Baru
        </button>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Potongan</th>
                    <th>Minimal Beli</th>
                    <th>Periode</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $diskon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($d->barang->nama_barang); ?></td>
                    <td>
                        <?php echo e($d->jenis_diskon == 'persentase' ? $d->nilai_diskon.'%' : 'Rp '.number_format($d->nilai_diskon)); ?>

                    </td>
                    <td><?php echo e($d->minimal_beli); ?> pcs</td>
                    <td><?php echo e(date('d M', strtotime($d->tgl_mulai))); ?> - <?php echo e(date('d M Y', strtotime($d->tgl_selesai))); ?></td>
                    <td>
                        <?php if(now()->between($d->tgl_mulai, $d->tgl_selesai)): ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Menunggu/Berakhir</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="modalDiskon">
    <div class="modal-dialog">
        <form action="<?php echo e(route('diskon.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header"><h4>Set Jadwal Diskon</h4></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Barang</label>
                        <select name="barang_id" class="form-control" required>
                            <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($b->id); ?>"><?php echo e($b->nama_barang); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Jenis</label>
                            <select name="jenis_diskon" class="form-control">
                                <option value="persentase">Persentase (%)</option>
                                <option value="nominal">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Nilai Potongan</label>
                            <input type="number" name="nilai_diskon" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label>Minimal Pembelian (Qty)</label>
                        <input type="number" name="minimal_beli" class="form-control" value="1">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Tgl Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label>Tgl Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan Jadwal</button></div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\Fuzzamart\resources\views/admin/diskon/index.blade.php ENDPATH**/ ?>