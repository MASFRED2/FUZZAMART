<?php $__env->startSection('isi'); ?>
<div class="container-fluid pt-4">
    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm" style="background-color: #22C55E; color: white;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm bg-white">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="font-weight-bold" style="color: #334155;">📦 Riwayat Pengadaan & Stok Masuk</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalStokMasuk" style="background-color: #B69377;">
                    <i class="fas fa-download mr-2"></i> Catat Barang Masuk
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Tgl Masuk</th>
                            <th style="background-color: #1E293B; color: white;">Nama Barang</th>
                            <th style="background-color: #1E293B; color: white;">Pemasok / Vendor</th>
                            <th style="background-color: #1E293B; color: white;">Jumlah</th>
                            <th style="background-color: #1E293B; color: white;">Harga Beli (Satuan)</th>
                            <th class="rounded-right" style="background-color: #1E293B; color: white;">Tgl Expired</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $stok_masuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-muted"><?php echo e($sm->created_at->format('d/m/Y')); ?></td>
                            <td class="font-weight-bold" style="color: #334155;"><?php echo e($sm->barang->nama_barang); ?></td>
                            <td>
                                <span class="badge badge-light px-2 py-1 border text-secondary">
                                    <i class="fas fa-handshake mr-1"></i> <?php echo e($sm->pemasok->nama_pemasok ?? 'Tanpa Vendor'); ?>

                                </span>
                            </td>
                            <td><span class="font-weight-bold" style="color: #334155;"><?php echo e($sm->jumlah_masuk); ?></span> pcs</td>
                            <td>Rp <?php echo e(number_format($sm->harga_beli, 0, ',', '.')); ?></td>
                            <td>
                                <?php if($sm->tgl_kadaluwarsa): ?>
                                    <span class="badge <?php echo e($sm->tgl_kadaluwarsa->isPast() ? 'badge-danger' : 'badge-warning text-white'); ?> px-2 py-1">
                                        <?php echo e($sm->tgl_kadaluwarsa->format('d/m/Y')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
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

<!-- Modal Input Stok Masuk -->
<div class="modal fade" id="modalStokMasuk" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="<?php echo e(route('stok-masuk.store')); ?>" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            <?php echo csrf_field(); ?>
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Form Penerimaan Barang</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Pilih Barang</label>
                    <select name="barang_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih item barang --</option>
                        <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>"><?php echo e($b->nama_barang); ?> (Stok saat ini: <?php echo e($b->stok_total); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Mitra Pemasok (Supplier)</label>
                    <select name="pemasok_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih perusahaan vendor --</option>
                        <?php $__currentLoopData = $pemasok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->nama_pemasok); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Jumlah Masuk</label>
                        <input type="number" name="jumlah_masuk" class="form-control" min="1" placeholder="0" required style="border-radius: 8px;">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label class="small font-weight-bold text-uppercase text-muted">Harga Beli / Pcs</label>
                        <input type="number" name="harga_beli" class="form-control" placeholder="Rp" required style="border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">Tanggal Kadaluwarsa (Opsional)</label>
                    <input type="date" name="tgl_kadaluwarsa" class="form-control" style="border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Logistik</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/admin/stok_masuk/index.blade.php ENDPATH**/ ?>