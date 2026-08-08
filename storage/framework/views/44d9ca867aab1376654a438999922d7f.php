<?php $__env->startSection('judul', 'Manajemen Data Barang'); ?>

<?php $__env->startSection('isi'); ?>
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4 mb-2"><label>Cari Barang</label><input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Nama barang atau barcode"></div>
            <div class="col-md-3 mb-2"><label>Kategori</label><select name="kategori_id" class="custom-select"><option value="">Semua kategori</option><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($kat->id); ?>" <?php if(request('kategori_id')==$kat->id): echo 'selected'; endif; ?>><?php echo e($kat->nama_kategori); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div class="col-md-2 mb-2"><label>Status</label><select name="status" class="custom-select"><option value="aktif" <?php if(request('status','aktif')==='aktif'): echo 'selected'; endif; ?>>Aktif</option><option value="nonaktif" <?php if(request('status')==='nonaktif'): echo 'selected'; endif; ?>>Nonaktif</option></select></div>
            <div class="col-md-3 mb-2 d-flex"><button class="btn btn-dark mr-2"><i class="fas fa-search mr-1"></i>Filter</button><a href="<?php echo e(route('barang.index')); ?>" class="btn btn-light mr-2">Reset</a><button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#modalTambahBarang"><i class="fas fa-plus mr-1"></i>Barang</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead><tr><th>No</th><th>Barcode</th><th>Barang</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th width="150">Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $barang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($barang->firstItem() + $loop->index); ?></td>
                        <td><span class="badge badge-secondary"><?php echo e($b->barcode); ?></span></td>
                        <td><strong><?php echo e($b->nama_barang); ?></strong><br><small class="text-muted">Satuan: <?php echo e($b->satuan ?? 'pcs'); ?></small></td>
                        <td><?php echo e($b->kategori?->nama_kategori ?? '-'); ?></td>
                        <td>Rp <?php echo e(number_format($b->harga_jual,0,',','.')); ?></td>
                        <td><?php if($b->stok_total <= 0): ?><span class="badge badge-danger">Habis</span><?php elseif($b->stok_total <= $b->stok_minimal): ?><span class="badge badge-warning"><?php echo e($b->stok_total); ?> rendah</span><?php else: ?><span class="badge badge-success"><?php echo e($b->stok_total); ?></span><?php endif; ?><br><small class="text-muted">Min: <?php echo e($b->stok_minimal); ?></small></td>
                        <td><span class="badge badge-<?php echo e($b->is_active ? 'success' : 'secondary'); ?>"><?php echo e($b->is_active ? 'Aktif' : 'Nonaktif'); ?></span></td>
                        <td>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalEdit<?php echo e($b->id); ?>"><i class="fas fa-edit"></i></button>
                            <form action="<?php echo e(route('barang.destroy',$b->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus/arsipkan barang ini? Riwayat transaksi tetap aman.')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit<?php echo e($b->id); ?>">
                        <div class="modal-dialog modal-lg"><form action="<?php echo e(route('barang.update',$b->id)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Barang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                <div class="modal-body"><div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Barcode</label><input type="text" name="barcode" value="<?php echo e($b->barcode); ?>" class="form-control" required></div>
                                        <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" value="<?php echo e($b->nama_barang); ?>" class="form-control" required></div>
                                        <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($kat->id); ?>" <?php if($b->kategori_id==$kat->id): echo 'selected'; endif; ?>><?php echo e($kat->nama_kategori); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan" value="<?php echo e($b->satuan ?? 'pcs'); ?>" class="form-control"></div>
                                        <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" value="<?php echo e($b->harga_jual); ?>" class="form-control" required></div>
                                        <div class="form-group"><label>Harga Beli Terakhir</label><input type="number" name="harga_beli_terakhir" value="<?php echo e($b->harga_beli_terakhir); ?>" class="form-control"></div>
                                        <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" value="<?php echo e($b->stok_minimal); ?>" class="form-control" required></div>
                                        <div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="aktif<?php echo e($b->id); ?>" name="is_active" value="1" <?php if($b->is_active): echo 'checked'; endif; ?>><label class="custom-control-label" for="aktif<?php echo e($b->id); ?>">Barang aktif dijual</label></div>
                                    </div>
                                </div></div>
                                <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Perubahan</button></div>
                            </div>
                        </form></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Data barang belum tersedia.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer"><?php echo e($barang->links()); ?></div>
</div>

<div class="modal fade" id="modalTambahBarang">
    <div class="modal-dialog modal-lg"><form action="<?php echo e(route('barang.store')); ?>" method="POST"><?php echo csrf_field(); ?>
        <div class="modal-content"><div class="modal-header"><h5 class="modal-title">Tambah Data Barang</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body"><div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label>Barcode</label><input type="text" name="barcode" class="form-control" placeholder="Scan atau ketik barcode" required autofocus></div>
                    <div class="form-group"><label>Nama Barang</label><input type="text" name="nama_barang" class="form-control" required></div>
                    <div class="form-group"><label>Kategori</label><select name="kategori_id" class="custom-select" required><option value="">Pilih kategori</option><?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($kat->id); ?>"><?php echo e($kat->nama_kategori); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label>Satuan</label><input type="text" name="satuan" value="pcs" class="form-control"></div>
                    <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" class="form-control" required></div>
                    <div class="form-group"><label>Harga Beli Terakhir</label><input type="number" name="harga_beli_terakhir" class="form-control" value="0"></div>
                    <div class="form-group"><label>Stok Minimal</label><input type="number" name="stok_minimal" class="form-control" value="5" required></div>
                    <div class="form-group"><label>Stok Awal</label><input type="number" name="stok_total" class="form-control" value="0"><small class="text-muted">Saran: stok utama tetap dicatat lewat menu Stok Masuk.</small></div>
                </div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-dismiss="modal">Batal</button><button class="btn btn-primary">Simpan Barang</button></div>
        </div>
    </form></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\Fuzzamart\resources\views/admin/barang/index.blade.php ENDPATH**/ ?>