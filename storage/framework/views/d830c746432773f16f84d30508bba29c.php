<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Scrapboard Record Management</title>
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
            <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="<?php echo e(route('admin.scrapboard-records.index')); ?>">
                <span class="sr-brand-mark">
                    <i class="bi bi-cpu"></i>
                </span>
                <span>Scrapboard Record Management</span>
            </a>

            <div class="d-flex align-items-center gap-3 text-white flex-wrap justify-content-end">
                <span class="small text-white-50"><?php echo e(auth()->user()->email); ?></span>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="mb-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show sr-reveal" role="alert" style="--sr-delay: 80ms;">
                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show sr-reveal" role="alert" style="--sr-delay: 80ms;">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/layouts/app.blade.php ENDPATH**/ ?>