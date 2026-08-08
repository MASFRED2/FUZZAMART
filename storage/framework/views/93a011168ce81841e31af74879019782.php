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
                <h5 class="font-weight-bold" style="color: #334155;">🔍 Inventarisasi Stok (Stock Opname)</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalOpname" style="background-color: #B69377;">
                    <i class="fas fa-calculator mr-2"></i> Mulai Audit Stok
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Waktu Audit</th>
                            <th style="background-color: #1E293B; color: white;">Nama Barang</th>
                            <th style="background-color: #1E293B; color: white;">Stok Sistem</th>
                            <th style="background-color: #1E293B; color: white;">Stok Fisik</th>
                            <th style="background-color: #1E293B; color: white;">Selisih</th>
                            <th class="rounded-right" style="background-color: #1E293B; color: white;">Keterangan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $opname; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="text-muted"><?php echo e($o->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="font-weight-bold" style="color: #334155;"><?php echo e($o->barang->nama_barang); ?></td>
                            <td><?php echo e($o->stok_sistem); ?> pcs</td>
                            <td class="font-weight-bold" style="color: #334155;"><?php echo e($o->stok_fisik); ?> pcs</td>
                            <td>
                                <?php if($o->selisih < 0): ?>
                                    <span class="text-danger font-weight-bold"><?php echo e($o->selisih); ?> pcs (Kurang)</span>
                                <?php elseif($o->selisih > 0): ?>
                                    <span class="text-success font-weight-bold">+<?php echo e($o->selisih); ?> pcs (Berlebih)</span>
                                <?php else: ?>
                                    <span class="text-muted">Sesuai (0)</span>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?php echo e($o->keterangan); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat stock opname yang tercatat.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Stock Opname -->
<div class="modal fade" id="modalOpname" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="<?php echo e(route('stock-opname.store')); ?>" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            <?php echo csrf_field(); ?>
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Form Audit Fisik Barang</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Pilih Barang</label>
                    <select name="barang_id" class="form-control" required style="border-radius: 8px;">
                        <option value="">-- Pilih item untuk diaudit --</option>
                        <?php $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b->id); ?>"><?php echo e($b->nama_barang); ?> (Stok Komputer: <?php echo e($b->stok_total); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Jumlah Stok Fisik Riil (Di Rak)</label>
                    <input type="number" name="stok_fisik" class="form-control" placeholder="Hitung jumlah asli barang..." min="0" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">Keterangan Penyesuaian</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Barang rusak digigit tikus / Kesalahan input kasir" required style="border-radius: 8px;"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Eksekusi Audit</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\Albar\Downloads\PROJECT_KP_FUZZA_MART_FIXED\PROJECT-KP\resources\views/admin/stock_opname/index.blade.php ENDPATH**/ ?>