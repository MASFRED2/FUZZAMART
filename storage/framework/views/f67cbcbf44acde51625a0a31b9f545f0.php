<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login | Fuzza Mart POS</title>
    <link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
    <style>
        body{min-height:100vh;margin:0;font-family:"Segoe UI",Arial,sans-serif;background:radial-gradient(circle at 20% 20%,rgba(182,147,119,.28),transparent 30%),linear-gradient(135deg,#0f172a,#1e293b 60%,#111827);display:flex;align-items:center;justify-content:center;padding:18px}.login-box{width:420px;max-width:100%}.card{border:0;border-radius:24px;overflow:hidden;box-shadow:0 35px 80px rgba(0,0,0,.28)}.card-header{background:#0f172a;color:#fff;text-align:center;padding:32px 24px;border:0}.brand-title{font-size:1.7rem;font-weight:900;letter-spacing:.04em}.brand-title span{color:#b69377}.brand-subtitle{color:#94a3b8;font-size:.8rem;text-transform:uppercase;letter-spacing:.12em}.form-control{height:50px;border-radius:12px 0 0 12px!important}.input-group-text{border-radius:0 12px 12px 0!important;background:#f8fafc}.btn-login{height:50px;border-radius:12px;background:#b69377;border-color:#b69377;color:#fff;font-weight:800}.btn-login:hover{filter:brightness(.92);color:#fff}.demo-box{background:#f8fafc;border-radius:14px;padding:12px;font-size:.85rem;color:#64748b}
    </style>
</head>
<body>
<div class="login-box">
    <div class="card">
        <div class="card-header"><div class="brand-title">FUZZA <span>MART</span></div><div class="brand-subtitle">Point of Sale System</div></div>
        <div class="card-body p-4">
            <p class="text-muted text-center mb-4">Masuk menggunakan akun yang sudah terdaftar.</p>
            <form action="<?php echo e(route('login')); ?>" method="POST"><?php echo csrf_field(); ?>
                <div class="form-group"><label>Email</label><div class="input-group"><input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" placeholder="Email" required autofocus><div class="input-group-append"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div></div><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <div class="form-group"><label>Password</label><div class="input-group"><input type="password" name="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Password" required><div class="input-group-append"><span class="input-group-text"><i class="fas fa-lock"></i></span></div></div><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                <div class="custom-control custom-checkbox mb-3"><input type="checkbox" name="remember" class="custom-control-input" id="remember"><label class="custom-control-label" for="remember">Ingat saya</label></div>
                <button type="submit" class="btn btn-login btn-block">Masuk Sistem <i class="fas fa-arrow-right ml-1"></i></button>
            </form>
            <div class="demo-box mt-4"><b>Akun demo</b><br>Admin: admin@gmail.com / admin@123<br>Kasir: kasir@gmail.com / kasir@456</div>
            <div class="text-center mt-3"><a href="<?php echo e(route('home')); ?>" class="text-muted"><i class="fas fa-arrow-left mr-1"></i>Kembali ke halaman utama</a></div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/js/adminlte.min.js')); ?>"></script>
</body>
</html>
<?php /**PATH E:\laragon\www\Fuzzamart\resources\views/auth/login.blade.php ENDPATH**/ ?>