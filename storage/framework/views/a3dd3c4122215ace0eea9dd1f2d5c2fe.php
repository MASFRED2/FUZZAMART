<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
        <li class="nav-item d-none d-sm-inline-block"><a href="<?php echo e(route('dashboard')); ?>" class="nav-link font-weight-bold">Fuzza Mart POS</a></li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <?php if(in_array(Auth::user()->role, ['admin','kasir'])): ?>
            <li class="nav-item mr-2 d-none d-md-block"><a href="<?php echo e(route('penjualan.index')); ?>" class="btn btn-sm btn-warning"><i class="fas fa-shopping-cart mr-1"></i> Buka Kasir</a></li>
        <?php endif; ?>
        <li class="nav-item mr-2 text-muted small d-none d-md-block"><?php echo e(Auth::user()->email); ?></li>
        <li class="nav-item">
            <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Keluar</button></form>
        </li>
    </ul>
</nav>
<?php /**PATH E:\laragon\www\Fuzzamart\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>