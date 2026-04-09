<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Scrapboard Code Index</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/scraprec-ui.css')); ?>" rel="stylesheet">
</head>
<body class="sr-app-body">
    <nav class="navbar navbar-expand-lg navbar-dark sr-navbar mb-4">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="<?php echo e(route('public.index')); ?>">
                <span class="sr-brand-mark">
                    <i class="bi bi-cpu"></i>
                </span>
                <span>Scrapboard Code Index</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('admin.scrapboard-records.index')); ?>" class="btn btn-sm btn-sr-subtle px-3">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="mb-0">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">Logout</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-sm btn-outline-light rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-person-lock me-1"></i> Login
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main
        class="container pb-5"
        data-public-index
        data-index-endpoint="<?php echo e(route('public.index')); ?>"
    >
        <section class="sr-card sr-card-soft p-4 mb-4 sr-data-card" style="--sr-delay: 30ms;">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <p class="sr-muted text-uppercase small fw-semibold mb-2">Public Lookup</p>
                    <h1 class="sr-page-title mb-1">Search Scrapboard Codes</h1>
                    <p class="sr-muted mb-0">Type a code to instantly view its classification (A1 to A5).</p>
                </div>
                <span class="badge text-bg-light border fs-6" data-public-total>
                    <?php echo e(number_format($records->total())); ?> total records
                </span>
            </div>
        </section>

        <section class="sr-card p-3 p-md-4 mb-4 sr-data-card" style="--sr-delay: 90ms;">
            <form method="GET" action="<?php echo e(route('public.index')); ?>" class="sr-form-shell" data-public-search-form>
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label for="q" class="form-label">Search by Code</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input
                                id="q"
                                name="q"
                                type="text"
                                class="form-control border-start-0"
                                value="<?php echo e(request('q')); ?>"
                                placeholder="Ex: IP13-A3-0045"
                                autocomplete="off"
                                data-public-search-input
                            >
                        </div>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-sr-primary">Search</button>
                    </div>
                </div>
            </form>
        </section>

        <section class="sr-card p-3 p-md-4 sr-data-card sr-relative" style="--sr-delay: 150ms;">
            <div class="sr-loading-overlay d-none" data-public-loading>
                <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
            </div>
            <div data-public-table-wrapper>
                <?php echo $__env->make('public.partials.records-table', ['records' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </section>
    </main>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold" id="loginModalLabel">Admin Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="sr-muted small mb-3">Sign in to access the admin dashboard and manage records.</p>

                    <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="login_email" class="form-label">Email</label>
                            <input
                                id="login_email"
                                type="email"
                                name="email"
                                value="<?php echo e(old('email')); ?>"
                                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                            >
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label for="login_password" class="form-label">Password</label>
                            <input
                                id="login_password"
                                type="password"
                                name="password"
                                class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                required
                            >
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" <?php if(old('remember')): echo 'checked'; endif; ?>>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-sr-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?php echo e(asset('js/public-index.js')); ?>"></script>
    <?php if($errors->has('email') || $errors->has('password')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/public/index.blade.php ENDPATH**/ ?>