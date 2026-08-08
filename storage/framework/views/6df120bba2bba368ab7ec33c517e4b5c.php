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
                <h5 class="font-weight-bold" style="color: #334155;">💳 Program Kemitraan & Member Pelanggan</h5>
                <button class="btn text-white px-4" data-toggle="modal" data-target="#modalPelanggan" style="background-color: #B69377;">
                    <i class="fas fa-user-plus mr-2"></i> Registrasi Member
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead>
                        <tr>
                            <th class="rounded-left" style="background-color: #1E293B; color: white;">Nama Member</th>
                            <th style="background-color: #1E293B; color: white;">Kontak / Email</th>
                            <th style="background-color: #1E293B; color: white;">No. Telepon</th>
                            <th style="background-color: #1E293B; color: white;">Poin Terkumpul</th>
                            <th class="rounded-right text-center" style="background-color: #1E293B; color: white;" width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pelanggan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#FDF8F3'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="font-weight-bold" style="color: #334155;"><?php echo e($p->name); ?></td>
                            <td class="text-muted"><?php echo e($p->email); ?></td>
                            <td><?php echo e($p->telepon ?? '-'); ?></td>
                            <td>
                                <span class="badge px-3 py-2 text-white font-weight-bold" style="background-color: #F59E0B; border-radius: 20px;">
                                    ⭐ <?php echo e($p->poin_loyalitas); ?> Poin
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="<?php echo e(route('pelanggan.destroy', $p->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm text-white" style="background-color: #EF4444;" onclick="return confirm('Hapus keanggotaan member ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="<?php echo e(route('pelanggan.store')); ?>" method="POST" class="modal-content border-0" style="border-radius: 12px;">
            <?php echo csrf_field(); ?>
            <div class="modal-header border-0 p-4">
                <h5 class="font-weight-bold" style="color: #334155;">Form Registrasi Member Baru</h5>
            </div>
            <div class="modal-body px-4 py-0">
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama sesuai KTP" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-3">
                    <label class="small font-weight-bold text-uppercase text-muted">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Contoh: member@gmail.com" required style="border-radius: 8px;">
                </div>
                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-uppercase text-muted">Nomor WhatsApp / HP</label>
                    <input type="text" name="telepon" class="form-control" placeholder="Contoh: 08XXXXXXXXXX" required style="border-radius: 8px;">
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn btn-light px-4" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn text-white px-4" style="background-color: #B69377; border-radius: 8px;">Simpan Keanggotaan</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\Fuzzamart\resources\views/admin/pelanggan/index.blade.php ENDPATH**/ ?>