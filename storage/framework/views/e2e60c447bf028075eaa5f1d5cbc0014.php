<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول | لوحة التحكم</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/adminlte.css')); ?>">
</head>
<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="link-dark text-decoration-none">
                    <h1 class="mb-0"><b>Admin</b>LTE</h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg text-center">سجّل دخولك لبدء الجلسة</p>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 fs-7" style="list-style: none; padding: 0;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(url('/login')); ?>" method="POST">
                    <?php echo csrf_field(); ?> 

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="البريد الإلكتروني" value="<?php echo e(old('email')); ?>" required autofocus>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="كلمة المرور" required>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-7">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">تذكرني</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">دخول</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo e(asset('assets/js/adminlte.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\gaza-stock-exchange\resources\views/login.blade.php ENDPATH**/ ?>