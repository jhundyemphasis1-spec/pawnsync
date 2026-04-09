(function () {
    const root = document.querySelector('[data-admin-dashboard]');
    if (!root) {
        return;
    }

    const indexEndpoint = root.dataset.indexEndpoint;
    const storeEndpoint = root.dataset.storeEndpoint;

    const filterForm = root.querySelector('[data-admin-filter-form]');
    const searchInput = root.querySelector('[data-admin-search-input]');
    const classificationFilter = root.querySelector('[data-admin-classification-filter]');
    const resetButton = root.querySelector('[data-admin-reset]');
    const tableWrapper = root.querySelector('[data-admin-table-wrapper]');
    const statsWrapper = root.querySelector('[data-admin-stats-wrapper]');
    const loadingEl = root.querySelector('[data-admin-loading]');
    const totalBadge = root.querySelector('[data-admin-total-badge]');

    const modalEl = document.getElementById('recordModal');
    const modalTitle = document.getElementById('recordModalLabel');
    const recordForm = document.querySelector('[data-admin-record-form]');
    const openCreateButton = root.querySelector('[data-open-create-modal]');
    const formMethodInput = document.querySelector('[data-admin-form-method]');
    const formCode = document.querySelector('[data-admin-form-code]');
    const formClassification = document.querySelector('[data-admin-form-classification]');
    const formSubmit = document.querySelector('[data-admin-form-submit]');
    const toastStack = document.querySelector('[data-admin-toast-stack]');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    if (
        !filterForm ||
        !searchInput ||
        !classificationFilter ||
        !tableWrapper ||
        !statsWrapper ||
        !recordForm ||
        !formMethodInput ||
        !formCode ||
        !formClassification ||
        !formSubmit
    ) {
        return;
    }

    const modal = modalEl && window.bootstrap?.Modal ? new window.bootstrap.Modal(modalEl) : null;
    let activeController = null;
    let debounceTimer = null;
    let currentUrl = window.location.href;

    const setLoading = (isLoading) => {
        loadingEl?.classList.toggle('d-none', !isLoading);
    };

    const clearErrors = () => {
        recordForm.querySelectorAll('[data-error-for]').forEach((el) => {
            el.classList.add('d-none');
            el.textContent = '';
        });
        recordForm.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    };

    const showErrors = (errors) => {
        Object.entries(errors || {}).forEach(([key, values]) => {
            const input = recordForm.querySelector(`[name="${key}"]`);
            const errorEl = recordForm.querySelector(`[data-error-for="${key}"]`);
            if (input) {
                input.classList.add('is-invalid');
            }
            if (errorEl) {
                errorEl.textContent = Array.isArray(values) ? values[0] : String(values);
                errorEl.classList.remove('d-none');
            }
        });
    };

    const showToast = (message, type = 'success') => {
        if (!toastStack) {
            return;
        }

        const toast = document.createElement('div');
        toast.className = `sr-toast sr-toast-${type}`;
        toast.innerHTML = `
            <span>${message}</span>
            <button type="button" class="btn-close" aria-label="Close"></button>
        `;
        toastStack.appendChild(toast);

        const close = () => toast.remove();
        toast.querySelector('.btn-close')?.addEventListener('click', close);
        window.setTimeout(close, 3000);
    };

    const buildListUrl = (base, params) => {
        const url = new URL(base, window.location.origin);
        Object.entries(params).forEach(([key, value]) => {
            if (!value) {
                return;
            }
            url.searchParams.set(key, value);
        });
        return url.toString();
    };

    const updateFilterInputsFromUrl = (url) => {
        const current = new URL(url, window.location.origin);
        const q = current.searchParams.get('q') || '';
        const classification = current.searchParams.get('classification') || '';
        searchInput.value = q;
        classificationFilter.value = classification;
    };

    const fetchList = async (url, pushState = true) => {
        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        setLoading(true);

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                signal: activeController.signal,
            });

            if (!response.ok) {
                throw new Error('Failed to refresh the dashboard.');
            }

            const payload = await response.json();
            tableWrapper.innerHTML = payload.table;
            statsWrapper.innerHTML = payload.stats;
            totalBadge.textContent = `${Number(payload.total).toLocaleString()} records`;
            currentUrl = url;

            updateFilterInputsFromUrl(url);

            if (pushState) {
                window.history.replaceState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
                showToast('Could not load records. Please refresh.', 'danger');
            }
        } finally {
            setLoading(false);
        }
    };

    const requestWithJson = async (url, method, body) => {
        const response = await fetch(url, {
            method,
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
            body,
        });

        if (response.status === 422) {
            const payload = await response.json();
            throw { type: 'validation', errors: payload.errors || {} };
        }

        if (!response.ok) {
            throw new Error('Request failed.');
        }

        return response.json();
    };

    const openCreateModal = () => {
        clearErrors();
        recordForm.reset();
        recordForm.dataset.mode = 'create';
        recordForm.dataset.recordId = '';
        formMethodInput.value = 'POST';
        modalTitle.textContent = 'New Scrapboard Code';
        formSubmit.textContent = 'Save Record';
        modal?.show();
    };

    const openEditModal = (row) => {
        clearErrors();
        recordForm.dataset.mode = 'edit';
        recordForm.dataset.recordId = row.dataset.recordId;
        formMethodInput.value = 'PUT';
        formCode.value = row.dataset.recordCode || '';
        formClassification.value = row.dataset.recordClassification || '';
        modalTitle.textContent = 'Edit Scrapboard Code';
        formSubmit.textContent = 'Update Record';
        modal?.show();
    };

    openCreateButton?.addEventListener('click', openCreateModal);

    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = buildListUrl(indexEndpoint, {
            q: searchInput.value.trim(),
            classification: classificationFilter.value,
        });
        fetchList(url);
    });

    resetButton?.addEventListener('click', (event) => {
        event.preventDefault();
        searchInput.value = '';
        classificationFilter.value = '';
        fetchList(indexEndpoint);
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const url = buildListUrl(indexEndpoint, {
                q: searchInput.value.trim(),
                classification: classificationFilter.value,
            });
            fetchList(url);
        }, 300);
    });

    classificationFilter.addEventListener('change', () => {
        const url = buildListUrl(indexEndpoint, {
            q: searchInput.value.trim(),
            classification: classificationFilter.value,
        });
        fetchList(url);
    });

    root.addEventListener('click', (event) => {
        const pageLink = event.target.closest('.pagination a');
        if (pageLink && tableWrapper.contains(pageLink)) {
            event.preventDefault();
            fetchList(pageLink.href);
            return;
        }

        const editButton = event.target.closest('[data-edit-record]');
        if (editButton) {
            const row = editButton.closest('tr[data-record-id]');
            if (!row) {
                return;
            }
            event.preventDefault();
            openEditModal(row);
        }
    });

    root.addEventListener('submit', async (event) => {
        const deleteForm = event.target.closest('[data-delete-form]');
        if (deleteForm) {
            event.preventDefault();
            if (!window.confirm('Delete this record?')) {
                return;
            }

            const body = new FormData(deleteForm);
            try {
                const payload = await requestWithJson(deleteForm.action, 'POST', body);
                await fetchList(currentUrl, false);
                showToast(payload.message || 'Record deleted.');
            } catch (error) {
                console.error(error);
                showToast('Delete failed. Please try again.', 'danger');
            }
            return;
        }

        if (event.target !== recordForm) {
            return;
        }

        event.preventDefault();
        clearErrors();

        const formData = new FormData(recordForm);
        const mode = recordForm.dataset.mode || 'create';
        const recordId = recordForm.dataset.recordId;

        let url = storeEndpoint;
        if (mode === 'edit' && recordId) {
            url = `${storeEndpoint}/${recordId}`;
            formData.set('_method', 'PUT');
        } else {
            formData.set('_method', 'POST');
        }

        formSubmit.disabled = true;
        formSubmit.textContent = mode === 'edit' ? 'Updating...' : 'Saving...';

        try {
            const payload = await requestWithJson(url, 'POST', formData);
            modal?.hide();
            await fetchList(currentUrl, false);
            showToast(payload.message || 'Saved successfully.');
        } catch (error) {
            if (error.type === 'validation') {
                showErrors(error.errors);
            } else {
                console.error(error);
                showToast('Save failed. Please try again.', 'danger');
            }
        } finally {
            formSubmit.disabled = false;
            formSubmit.textContent = mode === 'edit' ? 'Update Record' : 'Save Record';
        }
    });
})();
