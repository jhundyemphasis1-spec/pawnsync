<?php $__env->startSection('content'); ?>
    <div
        data-admin-dashboard
        data-index-endpoint="<?php echo e(route('admin.scrapboard-records.index')); ?>"
        data-store-endpoint="<?php echo e(route('admin.scrapboard-records.store')); ?>"
    >
        <section class="sr-card sr-card-soft p-4 mb-4 sr-data-card" style="--sr-delay: 40ms;">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <p class="sr-muted mb-2 text-uppercase small fw-semibold">Admin Dashboard</p>
                    <h1 class="sr-page-title mb-1">Scrapboard Codes</h1>
                    <p class="sr-muted mb-0">Manage records in real time with fast search, filtering, and inline actions.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('public.index')); ?>" class="btn btn-sr-subtle">
                        <i class="bi bi-box-arrow-up-right me-1"></i> View Public Index
                    </a>
                    <button type="button" class="btn btn-sr-primary" data-open-create-modal>
                        <i class="bi bi-plus-circle me-1"></i> New Code
                    </button>
                </div>
            </div>
        </section>

        <div data-admin-stats-wrapper>
            <?php echo $__env->make('scrapboard-records.partials.stats', ['stats' => $stats], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <section class="sr-card p-3 p-md-4 sr-data-card" style="--sr-delay: 200ms;">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <div>
                    <h2 class="sr-card-title mb-1">Code List</h2>
                    <p class="sr-muted mb-0 small">AJAX-enabled records table with instant updates.</p>
                </div>
                <span class="badge text-bg-light border" data-admin-total-badge><?php echo e(number_format($records->total())); ?> records</span>
            </div>

            <form method="GET" action="<?php echo e(route('admin.scrapboard-records.index')); ?>" class="sr-form-shell mb-3" data-admin-filter-form>
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="q" class="form-label">Search Code</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input
                                type="text"
                                id="q"
                                name="q"
                                value="<?php echo e(request('q')); ?>"
                                class="form-control border-start-0"
                                placeholder="Ex: IP13-A3-0045"
                                autocomplete="off"
                                data-admin-search-input
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="classification_filter" class="form-label">Classification</label>
                        <select id="classification_filter" name="classification" class="form-select" data-admin-classification-filter>
                            <option value="">All</option>
                            <?php $__currentLoopData = ['A1', 'A2', 'A3', 'A4', 'A5']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($classification); ?>" <?php if(request('classification') === $classification): echo 'selected'; endif; ?>><?php echo e($classification); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid d-md-flex gap-2">
                        <button type="submit" class="btn btn-sr-primary flex-fill">Apply</button>
                        <a href="<?php echo e(route('admin.scrapboard-records.index')); ?>" class="btn btn-sr-subtle flex-fill" data-admin-reset>Reset</a>
                    </div>
                </div>
            </form>

            <div class="sr-relative">
                <div class="sr-loading-overlay d-none" data-admin-loading>
                    <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
                </div>

                <div data-admin-table-wrapper>
                    <?php echo $__env->make('scrapboard-records.partials.records-table', ['records' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="recordModal" tabindex="-1" aria-labelledby="recordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold" id="recordModalLabel">New Scrapboard Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <form method="POST" action="<?php echo e(route('admin.scrapboard-records.store')); ?>" data-admin-record-form>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" value="POST" data-admin-form-method>

                        <div class="mb-3">
                            <label class="form-label" for="modal_code">Scrapboard Code</label>
                            <input
                                id="modal_code"
                                name="code"
                                type="text"
                                class="form-control"
                                maxlength="255"
                                required
                                data-admin-form-code
                            >
                            <div class="invalid-feedback d-block d-none" data-error-for="code"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="modal_classification">Classification</label>
                            <select id="modal_classification" name="classification" class="form-select" required data-admin-form-classification>
                                <option value="">Select Classification</option>
                                <?php $__currentLoopData = ['A1', 'A2', 'A3', 'A4', 'A5']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($classification); ?>"><?php echo e($classification); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="invalid-feedback d-block d-none" data-error-for="classification"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-sr-primary" data-admin-form-submit>
                                Save Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="sr-toast-stack" data-admin-toast-stack></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/admin-dashboard.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ScrapRec\resources\views/scrapboard-records/index.blade.php ENDPATH**/ ?>