(function () {
    const root = document.querySelector('[data-public-index]');
    if (!root) {
        return;
    }

    const endpoint = root.dataset.indexEndpoint;
    const form = root.querySelector('[data-public-search-form]');
    const searchInput = root.querySelector('[data-public-search-input]');
    const tableWrapper = root.querySelector('[data-public-table-wrapper]');
    const loadingEl = root.querySelector('[data-public-loading]');
    const totalEl = root.querySelector('[data-public-total]');

    let activeController = null;
    let debounceTimer = null;

    const setLoading = (isLoading) => {
        if (!loadingEl) {
            return;
        }

        loadingEl.classList.toggle('d-none', !isLoading);
    };

    const buildUrl = (base, params) => {
        const url = new URL(base, window.location.origin);
        Object.entries(params).forEach(([key, value]) => {
            if (value === null || value === undefined || value === '') {
                return;
            }
            url.searchParams.set(key, value);
        });
        return url;
    };

    const requestTable = async (url, pushState = true) => {
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
                throw new Error('Failed to load records.');
            }

            const payload = await response.json();
            tableWrapper.innerHTML = payload.table;
            if (totalEl) {
                totalEl.textContent = `${Number(payload.total).toLocaleString()} total records`;
            }

            if (pushState) {
                window.history.replaceState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error(error);
            }
        } finally {
            setLoading(false);
        }
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const url = buildUrl(endpoint, { q: searchInput.value.trim() });
        requestTable(url.toString());
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const url = buildUrl(endpoint, { q: searchInput.value.trim() });
            requestTable(url.toString());
        }, 300);
    });

    root.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');
        if (!link || !tableWrapper.contains(link)) {
            return;
        }

        event.preventDefault();
        requestTable(link.href);
    });
})();
