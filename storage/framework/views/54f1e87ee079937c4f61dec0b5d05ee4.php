<div class="table-responsive">
    <table class="table sr-table align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Classification</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr
                    data-record-id="<?php echo e($record->id); ?>"
                    data-record-code="<?php echo e($record->code); ?>"
                    data-record-classification="<?php echo e($record->classification); ?>"
                >
                    <td><?php echo e(($records->currentPage() - 1) * $records->perPage() + $loop->iteration); ?></td>
                    <td class="sr-record-code"><?php echo e($record->code); ?></td>
                    <td>
                        <span class="sr-pill sr-class-pill sr-class-<?php echo e(strtolower($record->classification)); ?>">
                            <?php echo e($record->classification); ?>

                        </span>
                    </td>
                    <td><?php echo e($record->created_at->format('Y-m-d H:i')); ?></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="<?php echo e(route('admin.scrapboard-records.edit', $record)); ?>"
                                class="btn btn-sm btn-sr-subtle"
                                data-edit-record
                            >
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form
                                action="<?php echo e(route('admin.scrapboard-records.destroy', $record)); ?>"
                                method="POST"
                                class="d-inline"
                                data-delete-form
                            >
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button
                                    type="submit"
                                    class="btn btn-sm btn-sr-danger"
                                    data-delete-button
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center sr-empty">
                        <i class="bi bi-inboxes fs-4 d-block mb-2"></i>
                        No codes found.
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
<?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/scrapboard-records/partials/records-table.blade.php ENDPATH**/ ?>