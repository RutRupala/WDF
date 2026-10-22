/**
 * Practical 6: Fetch API, Search, Filter, Sort, Pagination, and LocalStorage Caching
 * Subject: Web Development Frameworks (ITUE203) - CHARUSAT FTE
 */

document.addEventListener('DOMContentLoaded', function () {
    let currentTab = 'events'; // 'events' | 'students' | 'faqs'
    let rawData = [];
    let filteredData = [];
    let currentPage = 1;
    let itemsPerPage = 6;
    let searchDebounceTimer = null;

    const dataContainer = document.getElementById('p6-data-container');
    const searchInput = document.getElementById('p6-search-input');
    const categorySelect = document.getElementById('p6-category-filter');
    const sortSelect = document.getElementById('p6-sort-select');
    const pageInfo = document.getElementById('p6-page-info');
    const prevBtn = document.getElementById('p6-prev-page');
    const nextBtn = document.getElementById('p6-next-page');
    const tabButtons = document.querySelectorAll('.p6-tab-btn');
    const statusBanner = document.getElementById('p6-status-banner');

    // Tab switcher
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            tabButtons.forEach(b => b.classList.remove('btn-primary'));
            tabButtons.forEach(b => b.classList.add('btn-secondary'));
            this.classList.remove('btn-secondary');
            this.classList.add('btn-primary');

            currentTab = this.dataset.tab;
            currentPage = 1;
            loadData();
        });
    });

    // Fetch and cache data
    async function loadData() {
        showLoading();
        const cacheKey = `p6_cache_${currentTab}`;
        const filePath = `data/${currentTab}.json`;

        try {
            const response = await fetch(filePath);
            if (!response.ok) throw new Error(`HTTP Error ${response.status}`);
            rawData = await response.json();
            localStorage.setItem(cacheKey, JSON.stringify(rawData));
            if (statusBanner) statusBanner.textContent = `Loaded ${rawData.length} records live from ${filePath}`;
        } catch (err) {
            console.warn('Network fetch failed, attempting localStorage cache...', err);
            const cached = localStorage.getItem(cacheKey);
            if (cached) {
                rawData = JSON.parse(cached);
                if (statusBanner) statusBanner.textContent = `Offline Mode: Loaded ${rawData.length} records from localStorage cache.`;
            } else {
                showError('Failed to fetch data and no cached fallback found.');
                return;
            }
        }

        populateCategoryFilter();
        applyFilterAndSort();
    }

    function showLoading() {
        dataContainer.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <div style="display: inline-block; width: 36px; height: 36px; border: 3px solid var(--border-color); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                <p style="margin-top: 12px; color: var(--text-muted);">Fetching JSON records via Fetch API...</p>
            </div>
            <style>@keyframes spin { to { transform: rotate(360deg); } }</style>
        `;
    }

    function showError(msg) {
        dataContainer.innerHTML = `
            <div style="grid-column: 1 / -1;" class="alert alert-danger">
                <strong>Error:</strong> ${msg}
            </div>
        `;
    }

    function populateCategoryFilter() {
        categorySelect.innerHTML = '<option value="ALL">All Categories</option>';
        const categories = new Set();
        rawData.forEach(item => {
            const cat = item.category || item.department;
            if (cat) categories.add(cat);
        });
        categories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.textContent = cat;
            categorySelect.appendChild(opt);
        });
    }

    function applyFilterAndSort() {
        const query = searchInput.value.trim().toLowerCase();
        const selectedCat = categorySelect.value;
        const sortVal = sortSelect.value;

        // 1. Filter
        filteredData = rawData.filter(item => {
            const nameOrTitle = (item.title || item.name || item.question || '').toLowerCase();
            const descOrAnswer = (item.venue || item.email || item.answer || '').toLowerCase();
            const cat = item.category || item.department || '';

            const matchesSearch = nameOrTitle.includes(query) || descOrAnswer.includes(query);
            const matchesCat = selectedCat === 'ALL' || cat === selectedCat;
            return matchesSearch && matchesCat;
        });

        // 2. Sort
        filteredData.sort((a, b) => {
            const textA = (a.title || a.name || a.question || '').toLowerCase();
            const textB = (b.title || b.name || b.question || '').toLowerCase();
            if (sortVal === 'az') return textA.localeCompare(textB);
            if (sortVal === 'za') return textB.localeCompare(textA);
            if (sortVal === 'num') {
                const valA = a.cgpa || a.seats || a.views || 0;
                const valB = b.cgpa || b.seats || b.views || 0;
                return valB - valA;
            }
            return 0;
        });

        renderPage();
    }

    function renderPage() {
        const totalItems = filteredData.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const pageItems = filteredData.slice(startIdx, startIdx + itemsPerPage);

        pageInfo.textContent = `Page ${currentPage} of ${totalPages} (${totalItems} total items)`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage >= totalPages;

        if (pageItems.length === 0) {
            dataContainer.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 2.5rem;" class="card">
                    <p style="color: var(--text-muted); font-size: 1.1rem;">🔍 No matching records found for your filter criteria.</p>
                </div>
            `;
            return;
        }

        dataContainer.innerHTML = '';
        pageItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'card card-hover';

            if (currentTab === 'events') {
                card.innerHTML = `
                    <div class="card-header">
                        <span class="badge badge-primary">${item.category}</span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">${item.date}</span>
                    </div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 8px;">${item.title}</h3>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 12px;">📍 ${item.venue} | 👤 ${item.organizer}</p>
                    <div class="card-footer">
                        <span class="badge badge-success">${item.seats} Seats Left</span>
                        <button class="btn btn-sm btn-primary" onclick="window.showToast('Selected event: ${item.title}', 'info')">View Details</button>
                    </div>
                `;
            } else if (currentTab === 'students') {
                card.innerHTML = `
                    <div class="card-header">
                        <span class="badge badge-info">${item.department}</span>
                        <span style="font-size: 0.85rem; font-weight: 700;">Roll: ${item.rollNo}</span>
                    </div>
                    <h3 style="font-size: 1.15rem; margin-bottom: 6px;">${item.name}</h3>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); margin-bottom: 12px;">✉️ ${item.email}</p>
                    <div class="card-footer">
                        <span class="badge badge-success">CGPA: ${item.cgpa}</span>
                        <span class="badge badge-primary">Semester ${item.semester}</span>
                    </div>
                `;
            } else if (currentTab === 'faqs') {
                card.innerHTML = `
                    <div class="card-header">
                        <span class="badge badge-warning">${item.category}</span>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">👀 ${item.views} Views</span>
                    </div>
                    <h3 style="font-size: 1.05rem; margin-bottom: 8px;">${item.question}</h3>
                    <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.6;">${item.answer}</p>
                `;
            }

            dataContainer.appendChild(card);
        });
    }

    // Event Listeners for search & pagination
    searchInput.addEventListener('input', () => {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            currentPage = 1;
            applyFilterAndSort();
        }, 250); // 250ms debounce
    });

    categorySelect.addEventListener('change', () => {
        currentPage = 1;
        applyFilterAndSort();
    });

    sortSelect.addEventListener('change', applyFilterAndSort);

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderPage();
        }
    });

    nextBtn.addEventListener('click', () => {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderPage();
        }
    });

    // Initial load
    loadData();
});
