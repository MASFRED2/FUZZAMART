<?php $__env->startSection('judul','Manajemen Kategori'); ?>
<?php $__env->startSection('isi'); ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0 font-weight-bold">Daftar Kategori Barang</h5><button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus mr-1"></i>Tambah Kategori</button></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0"><thead><tr><th>No</th><th>Nama Kategori</th><th>Jumlah Barang</th><th width="150">Aksi</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><?php echo e($loop->iteration); ?></td><td><strong><?php echo e($k->nama_kategori); ?></strong></td><td><span class="badge badge-info"><?php echo e($k->barang_count); ?> barang</span></td><td>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit<?php echo e($k->id); ?>"><i class="fas fa-edit"></i></button>
                    <form action="<?php echo e(route('kategori.destroy',$k->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></form>
                </td></tr>
                <div class="modal fade" id="edit<?php echo e($k->id); ?>"><div class="modal-dialog"><form action="<?php echo e(route('kategori.update',$k->id)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?><div class="modal-content"><div class="modal-header"><h5>Edit Kategori</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><label>Nama Kategori</label><input name="nama_kategori" value="<?php echo e($k->nama_kategori); ?>" class="form-control" required></div><div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div></div></form></div></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
            <?php endif; ?>
        </tbody></table>
    </div>
</div>
<div class="modal fade" id="modalTambah"><div class="modal-dialog"><form action="<?php echo e(route('kategori.store')); ?>" method="POST"><?php echo csrf_field(); ?><div class="modal-content"><div class="modal-header"><h5>Tambah Kategori</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><label>Nama Kategori</label><input type="text" name="nama_kategori" class="form-control" required></div><div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan</button></div></div></form></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\Fuzzamart\resources\views/admin/kategori/index.blade.php ENDPATH**/ ?>