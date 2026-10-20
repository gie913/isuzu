@extends('admin.layouts.theme', [
    'title' => __('label.users') . ' (Server-Side Ajax)',
    'activeMenu' => 'users',
    'breadcrumbsText' => 'Workspace | ' . __('label.users') . ' | Server-Side Ajax'
])

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Server-Side DataTable</span>
            <h1 class="hero-title">{{ __('label.users') }} <span class="badge solid primary" style="font-size:12px;vertical-align:middle;margin-left:8px;">AJAX</span></h1>
            <p class="hero-sub">Dynamic server-side pagination, search, status filtering, and sorting powered by asynchronous JSON API.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn--ghost">
                Standard View
            </a>
            <a href="{{ route('admin.users.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                {{ __('label.add_user') }}
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <span class="ico">
                <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
            <div class="body">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </span>
            <div class="body">{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="data-toolbar">
                <div class="data-toolbar-left">
                    <div class="input-icon" style="flex:1;max-width:320px">
                        <span class="ico">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input id="ajaxSearchInput" class="input" type="search" placeholder="Search name, username, email...">
                    </div>
                    <span class="badge primary" id="ajaxTotalBadge">Loading…</span>
                    <span id="ajaxLoadingSpinner" style="display:none;font-size:12px;color:var(--t-light);">
                        Fetching…
                    </span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Users Only</span>

                    <button id="ajaxRefreshBtn" class="btn btn--ghost btn--icon" title="Refresh data" aria-label="Refresh">
                        <svg viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    </button>
                </div>
            </div>

            <div style="overflow-x:auto;margin:0 -22px">
                <table class="data-table" id="ajaxUsersTable" style="margin:0 22px;min-width:900px">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="ajaxSelectAll"><span class="box"></span></label>
                            </th>
                            <th class="is-sortable" data-sort="name" style="cursor:pointer;">
                                {{ __('label.name') }} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
                            </th>
                            <th class="is-sortable" data-sort="username" style="cursor:pointer;">
                                {{ __('form.username') }} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
                            </th>
                            <th>{{ __('label.status') }}</th>
                            <th>{{ __('label.id') }}</th>
                            <th class="is-sortable" data-sort="created_at" style="cursor:pointer;">
                                {{ __('label.created_at') }} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
                            </th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="ajaxTableBody">
                        <tr>
                            <td colspan="7" style="text-align:center;padding:32px;color:var(--t-light);">
                                Loading data from server…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span id="ajaxPagingInfo">Showing <strong style="color:var(--t-base)">0–0</strong> of <strong style="color:var(--t-base)">0</strong></span>
                    <select id="ajaxPerPageSelect" class="select" style="width:auto;padding:5px 24px 5px 8px;font-size:12px">
                        <option value="10">10 per page</option>
                        <option value="15" selected>15 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
                <div class="pager" id="ajaxPaginationNav">
                    <!-- Dynamic pager buttons -->
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const endpoint = "{!! $ajaxEndpoint !!}";
    const tbody = document.getElementById('ajaxTableBody');
    const searchInput = document.getElementById('ajaxSearchInput');
    const statusFilter = document.getElementById('ajaxStatusFilter');
    const perPageSelect = document.getElementById('ajaxPerPageSelect');
    const totalBadge = document.getElementById('ajaxTotalBadge');
    const pagingInfo = document.getElementById('ajaxPagingInfo');
    const paginationNav = document.getElementById('ajaxPaginationNav');
    const spinner = document.getElementById('ajaxLoadingSpinner');
    const refreshBtn = document.getElementById('ajaxRefreshBtn');
    const selectAllCheckbox = document.getElementById('ajaxSelectAll');

    let state = {
        page: 1,
        per_page: 15,
        search: '',
        order_by: 'created_at',
        order_dir: 'desc'
    };

    let debounceTimer = null;

    function fetchTableData() {
        if (spinner) spinner.style.display = 'inline';

        const params = new URLSearchParams({
            page: state.page,
            per_page: state.per_page,
            search: state.search,
            order_by: state.order_by,
            order_dir: state.order_dir
        });

        fetch(`${endpoint}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network error ' + res.status);
            return res.json();
        })
        .then(json => {
            renderTable(json);
            renderPagination(json);
            if (spinner) spinner.style.display = 'none';
        })
        .catch(err => {
            console.error('Ajax Table Error:', err);
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--danger,#ef4444);">Failed to load data from server.</td></tr>`;
            if (spinner) spinner.style.display = 'none';
        });
    }

    function renderTable(response) {
        const rows = response.data || [];
        totalBadge.textContent = `${response.total} users`;

        if (response.total === 0 || rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:var(--t-light);">No users found.</td></tr>`;
            pagingInfo.innerHTML = `Showing <strong style="color:var(--t-base)">0</strong> of <strong style="color:var(--t-base)">0</strong>`;
            return;
        }

        pagingInfo.innerHTML = `Showing <strong style="color:var(--t-base)">${response.from}–${response.to}</strong> of <strong style="color:var(--t-base)">${response.total}</strong>`;

        let html = '';
        rows.forEach(item => {
            html += `
                <tr class="data-row">
                    <td>
                        <label class="check"><input type="checkbox" class="ajax-row-check" value="${item.id}"><span class="box"></span></label>
                    </td>
                    <td>
                        <div class="data-cell-user">
                            <div class="av ${item.avatar_class}">${item.initials}</div>
                            <div class="data-cell-user-meta">
                                <div class="data-cell-user-name">
                                    <a href="${item.detail_url}" style="color:inherit;text-decoration:none;font-weight:600;">
                                        ${item.name}
                                    </a>
                                </div>
                                <div class="data-cell-user-email">${item.email}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge info">${item.username}</span>
                    </td>
                    <td>
                        ${item.status_html}
                    </td>
                    <td>
                        <span class="data-cell-mono" title="${item.id}">${item.short_id}</span>
                    </td>
                    <td>
                        <span class="data-cell-mono">${item.created_at}</span>
                    </td>
                    <td>
                        ${item.actions}
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        // Rebind row selection
        const rowChecks = tbody.querySelectorAll('.ajax-row-check');
        rowChecks.forEach(cb => {
            cb.addEventListener('change', () => {
                const tr = cb.closest('tr');
                if (tr) tr.classList.toggle('is-selected', cb.checked);
            });
        });
    }

    function renderPagination(response) {
        const currentPage = response.current_page;
        const lastPage = response.last_page;

        if (lastPage <= 1) {
            paginationNav.innerHTML = '';
            return;
        }

        let navHtml = '';

        // Previous button
        const prevDisabled = currentPage <= 1 ? 'disabled="disabled"' : '';
        navHtml += `<button class="pager-btn" ${prevDisabled} data-page="${currentPage - 1}" aria-label="Previous">
            <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        </button>`;

        // Page window
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, currentPage + 2);

        if (startPage > 1) {
            navHtml += `<button class="pager-btn" data-page="1">1</button>`;
            if (startPage > 2) navHtml += `<button class="pager-btn" disabled>…</button>`;
        }

        for (let p = startPage; p <= endPage; p++) {
            const activeClass = p === currentPage ? 'is-active' : '';
            navHtml += `<button class="pager-btn ${activeClass}" data-page="${p}">${p}</button>`;
        }

        if (endPage < lastPage) {
            if (endPage < lastPage - 1) navHtml += `<button class="pager-btn" disabled>…</button>`;
            navHtml += `<button class="pager-btn" data-page="${lastPage}">${lastPage}</button>`;
        }

        // Next button
        const nextDisabled = currentPage >= lastPage ? 'disabled="disabled"' : '';
        navHtml += `<button class="pager-btn" ${nextDisabled} data-page="${currentPage + 1}" aria-label="Next">
            <svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        </button>`;

        paginationNav.innerHTML = navHtml;

        // Bind clicks
        paginationNav.querySelectorAll('.pager-btn:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetPage = parseInt(btn.getAttribute('data-page'));
                if (targetPage && targetPage !== state.page) {
                    state.page = targetPage;
                    fetchTableData();
                }
            });
        });
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                state.search = searchInput.value.trim();
                state.page = 1;
                fetchTableData();
            }, 300);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', () => {
            state.per_page = parseInt(perPageSelect.value);
            state.page = 1;
            fetchTableData();
        });
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            fetchTableData();
        });
    }

    // Sort headers
    document.querySelectorAll('#ajaxUsersTable th.is-sortable').forEach(th => {
        th.addEventListener('click', () => {
            const field = th.getAttribute('data-sort');
            if (state.order_by === field) {
                state.order_dir = state.order_dir === 'asc' ? 'desc' : 'asc';
            } else {
                state.order_by = field;
                state.order_dir = 'asc';
            }

            document.querySelectorAll('#ajaxUsersTable th.is-sortable').forEach(col => {
                col.classList.remove('sorted-asc', 'sorted-desc');
            });
            th.classList.add(state.order_dir === 'asc' ? 'sorted-asc' : 'sorted-desc');

            state.page = 1;
            fetchTableData();
        });
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            const checks = tbody.querySelectorAll('.ajax-row-check');
            checks.forEach(c => {
                c.checked = selectAllCheckbox.checked;
                const tr = c.closest('tr');
                if (tr) tr.classList.toggle('is-selected', selectAllCheckbox.checked);
            });
        });
    }

    // Initial load
    fetchTableData();
});
</script>
@endsection
