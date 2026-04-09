<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Scrapboard Record Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/scraprec-ui.css')); ?>" rel="stylesheet">
</head>
<body class="sr-auth-body">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4 py-md-5">
        <div class="w-100 sr-auth-wrap sr-auth-panel sr-reveal">
            <div class="row g-0">
                <div class="col-lg-6 sr-auth-side">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="sr-brand-mark">
                            <i class="bi bi-cpu"></i>
                        </span>
                        <span class="fw-semibold">ScrapRec Admin</span>
                    </div>
                    <h1 class="mb-2">Scrapboard Record Management</h1>
                    <p class="mb-0 opacity-75">Track Android and iOS motherboard codes with a clean and secure workflow.</p>

                    <ul class="sr-auth-list">
                        <li>
                            <i class="bi bi-shield-check"></i>
                            <span>Secure admin-only access for your motherboard inventory records.</span>
                        </li>
                        <li>
                            <i class="bi bi-grid-1x2"></i>
                            <span>Centralized dashboard for creating, editing, and filtering records quickly.</span>
                        </li>
                        <li>
                            <i class="bi bi-clock-history"></i>
                            <span>Time-based tracking to review newly added scrapboard entries.</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-6 sr-auth-form-area">
                    <div class="mb-4">
                        <h2 class="h4 fw-bold mb-1">Welcome Back</h2>
                        <p class="sr-muted mb-0">Please sign in to continue to the dashboard.</p>
                    </div>

                    <form method="POST" action="<?php echo e(route('login.attempt')); ?>" class="sr-auth-form">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3 sr-reveal" style="--sr-delay: 70ms;">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope"></i></span>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="<?php echo e(old('email')); ?>"
                                    class="form-control border-start-0 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="admin@scraprec.local"
                                    required
                                    autofocus
                                >
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3 sr-reveal" style="--sr-delay: 130ms;">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-key"></i></span>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="form-control border-start-0 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter your password"
                                    required
                                >
                            </div>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 sr-reveal" style="--sr-delay: 190ms;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" <?php if(old('remember')): echo 'checked'; endif; ?>>
                                <label class="form-check-label" for="remember">
                                    Keep me signed in
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-sr-primary w-100 py-2 sr-reveal" style="--sr-delay: 240ms;">Login to Dashboard</button>
                    </form>

                    <div class="sr-auth-credentials mt-4 sr-reveal" style="--sr-delay: 310ms;">
                        <strong>Default admin:</strong> admin@scraprec.local / admin12345
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/auth/login.blade.php ENDPATH**/ ?>