<div class="table-responsive">
    <table class="table sr-table align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Classification</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e(($records->currentPage() - 1) * $records->perPage() + $loop->iteration); ?></td>
                    <td class="sr-record-code"><?php echo e($record->code); ?></td>
                    <td>
                        <span class="sr-pill sr-class-pill sr-class-<?php echo e(strtolower($record->classification)); ?>">
                            <?php echo e($record->classification); ?>

                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" class="text-center sr-empty">
                        <i class="bi bi-search fs-4 d-block mb-2"></i>
                        No code found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if($records->hasPages()): ?>
    <div class="mt-3 sr-pagination-wrap">
        <?php echo e($records->links()); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/public/partials/records-table.blade.php ENDPATH**/ ?>