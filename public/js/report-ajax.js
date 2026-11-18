/**
 * TemuUNRI - AJAX Live Search & Dynamic Filter
 * Framework-free Vanilla JavaScript menggunakan Fetch API
 */
document.addEventListener('DOMContentLoaded', function () {
    const filterForm       = document.getElementById('report-filter-form');
    const searchInput      = document.getElementById('search');
    const tipeSelect       = document.getElementById('tipe');
    const kategoriInput    = document.getElementById('kategori');
    const listContainer    = document.getElementById('report-list-container');
    const totalCountBadge  = document.getElementById('total-count-badge');
    const activeFiltersBar = document.getElementById('active-filters-wrapper');
    const spinner          = document.getElementById('report-search-spinner');
    const inputSpinner     = document.getElementById('search-input-spinner');
    const errorBanner      = document.getElementById('report-ajax-error');
    const errorMessageEl   = document.getElementById('report-ajax-error-message');
    const retryBtn         = document.getElementById('report-ajax-retry-btn');
    const resetBtn         = document.getElementById('report-reset-btn');

    if (!listContainer || !filterForm) return;

    let currentAbortController = null;
    let lastUrl = window.location.href;

    // Helper debounce untuk delay input pengetikan (350 ms)
    function debounce(callback, delay = 350) {
        let timeoutId;
        return function (...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => callback.apply(this, args), delay);
        };
    }

    // Tampilkan / Sembunyikan Loading State
    function setLoading(isLoading) {
        if (isLoading) {
            if (spinner) spinner.classList.remove('hidden');
            if (inputSpinner) inputSpinner.classList.remove('hidden');
            listContainer.classList.add('opacity-50', 'pointer-events-none');
            if (errorBanner) errorBanner.classList.add('hidden');
        } else {
            if (spinner) spinner.classList.add('hidden');
            if (inputSpinner) inputSpinner.classList.add('hidden');
            listContainer.classList.remove('opacity-50', 'pointer-events-none');
        }
    }

    // Bangun URL dengan parameter aktif
    function buildRequestUrl(page = 1) {
        const baseUrl = filterForm.getAttribute('action') || window.location.pathname;
        const url = new URL(baseUrl, window.location.origin);

        const searchVal = searchInput ? searchInput.value.trim() : '';
        const tipeVal   = tipeSelect ? tipeSelect.value : '';
        const katVal    = kategoriInput ? kategoriInput.value.trim() : '';

        if (searchVal) url.searchParams.set('search', searchVal);
        if (tipeVal)   url.searchParams.set('tipe', tipeVal);
        if (katVal)    url.searchParams.set('kategori', katVal);
        if (page && page > 1) url.searchParams.set('page', page);

        return url.toString();
    }

    // Perbarui Tampilan Badge Filter Aktif
    function updateActiveFilterBadge() {
        if (!activeFiltersBar) return;
        const searchVal = searchInput ? searchInput.value.trim() : '';
        const tipeVal   = tipeSelect ? tipeSelect.value : '';
        const katVal    = kategoriInput ? kategoriInput.value.trim() : '';

        if (searchVal || tipeVal || katVal) {
            activeFiltersBar.classList.remove('hidden');
        } else {
            activeFiltersBar.classList.add('hidden');
        }
    }

    // Eksekusi Fetch AJAX ke Controller Laravel
    async function fetchReports(targetUrl, pushState = true) {
        // Batalkan request sebelumnya yang masih berjalan agar tidak terjadi race condition
        if (currentAbortController) {
            currentAbortController.abort();
        }
        currentAbortController = new AbortController();

        setLoading(true);

        try {
            const response = await fetch(targetUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: currentAbortController.signal,
            });

            if (!response.ok) {
                throw new Error('Gagal memuat data dari server (Status ' + response.status + ').');
            }

            const data = await response.json();

            // Perbarui HTML daftar kartu laporan
            if (typeof data.html !== 'undefined') {
                listContainer.innerHTML = data.html;
            }

            // Perbarui teks total laporan jika tersedia
            if (totalCountBadge && typeof data.total !== 'undefined') {
                totalCountBadge.textContent = data.total;
            }

            // Perbarui indikator filter aktif
            updateActiveFilterBadge();

            // Sinkronisasi URL browser via History API tanpa reload
            if (pushState && targetUrl !== window.location.href) {
                window.history.pushState({ url: targetUrl }, '', targetUrl);
                lastUrl = targetUrl;
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                // Request dibatalkan karena ada request baru, abaikan
                return;
            }

            console.error('AJAX Fetch Error:', error);
            if (errorBanner) {
                if (errorMessageEl) {
                    errorMessageEl.textContent = error.message || 'Terjadi kesalahan saat memuat data laporan.';
                }
                errorBanner.classList.remove('hidden');
            }
        } finally {
            setLoading(false);
        }
    }

    // Event Handler untuk Live Search dengan Debounce 350ms
    const handleSearchInput = debounce(function () {
        const url = buildRequestUrl(1);
        fetchReports(url);
    }, 350);

    if (searchInput) {
        searchInput.addEventListener('input', handleSearchInput);
    }

    // Event Handler untuk Kategori dengan Debounce 350ms
    const handleKategoriInput = debounce(function () {
        const url = buildRequestUrl(1);
        fetchReports(url);
    }, 350);

    if (kategoriInput) {
        kategoriInput.addEventListener('input', handleKategoriInput);
    }

    // Event Handler untuk Pilihan Jenis Laporan (tipe) onchange langsung
    if (tipeSelect) {
        tipeSelect.addEventListener('change', function () {
            const url = buildRequestUrl(1);
            fetchReports(url);
        });
    }

    // Intersep Pengiriman Form Standar
    filterForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const url = buildRequestUrl(1);
        fetchReports(url);
    });

    // Reset Filter via Tombol Reset
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (tipeSelect) tipeSelect.value = '';
        if (kategoriInput) kategoriInput.value = '';

        const baseUrl = filterForm.getAttribute('action') || window.location.pathname;
        fetchReports(baseUrl);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function (e) {
            e.preventDefault();
            resetFilters();
        });
    }

    // Klik tombol reset pada error banner atau empty state
    document.addEventListener('click', function (e) {
        const resetTrigger = e.target.closest('[data-action="reset-filter"]');
        if (resetTrigger) {
            e.preventDefault();
            resetFilters();
        }
    });

    if (retryBtn) {
        retryBtn.addEventListener('click', function () {
            fetchReports(lastUrl, false);
        });
    }

    // Intersep Klik Navigasi Pagination
    listContainer.addEventListener('click', function (e) {
        const pageLink = e.target.closest('.pagination-wrapper a, .pagination a, nav[role="navigation"] a');
        if (pageLink && pageLink.href) {
            e.preventDefault();
            const targetUrl = pageLink.href;

            fetchReports(targetUrl, true);

            // Scroll halus kembali ke batas daftar laporan agar nyaman dibaca
            const sectionTarget = document.getElementById('daftar-laporan');
            if (sectionTarget) {
                sectionTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });

    // Handle Navigasi Browser (Tombol Back / Forward)
    window.addEventListener('popstate', function () {
        const currentUrl = new URL(window.location.href);

        if (searchInput) searchInput.value = currentUrl.searchParams.get('search') || '';
        if (tipeSelect) tipeSelect.value = currentUrl.searchParams.get('tipe') || '';
        if (kategoriInput) kategoriInput.value = currentUrl.searchParams.get('kategori') || '';

        fetchReports(window.location.href, false);
    });
});
