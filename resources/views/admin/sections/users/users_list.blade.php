@extends('admin.layouts.theme', [
    'title' => __('label.users'),
    'activeMenu' => 'users',
    'breadcrumbsText' => 'Workspace | ' . __('label.users')
])

@section('css')
<style>
    /* Fixed Table Columns (Name & Actions) */
    .table-scroll-container {
        overflow-x: auto;
        margin: 0 -22px;
        position: relative;
    }

    #usersTable {
        margin: 0 22px;
        min-width: 1000px;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Fixed Checkbox column (1st column) */
    #usersTable th:nth-child(1),
    #usersTable td:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 2;
        background: var(--bg-card);
        width: 44px;
        min-width: 44px;
    }

    /* Fixed Name column (2nd column) */
    #usersTable th:nth-child(2),
    #usersTable td:nth-child(2) {
        position: sticky;
        left: 44px;
        z-index: 2;
        background: var(--bg-card);
        min-width: 220px;
        box-shadow: 2px 0 6px -2px rgba(0, 0, 0, 0.08);
    }

    /* Fixed Actions column (last column) */
    #usersTable th:last-child,
    #usersTable td:last-child {
        position: sticky;
        right: 0;
        z-index: 2;
        background: var(--bg-card);
        min-width: 140px;
        box-shadow: -2px 0 6px -2px rgba(0, 0, 0, 0.08);
    }

    /* Keep top-left and top-right headers above scrolling body cells */
    #usersTable thead th:nth-child(1),
    #usersTable thead th:nth-child(2),
    #usersTable thead th:last-child {
        z-index: 5;
    }

    /* Preserve hover & selected row backgrounds on sticky columns */
    #usersTable tbody tr:hover td:nth-child(1),
    #usersTable tbody tr:hover td:nth-child(2),
    #usersTable tbody tr:hover td:last-child {
        background: var(--bg-hover) !important;
    }

    #usersTable tbody tr.is-selected td:nth-child(1),
    #usersTable tbody tr.is-selected td:nth-child(2),
    #usersTable tbody tr.is-selected td:last-child {
        background: var(--primary-soft) !important;
    }
</style>
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Management</span>
            <h1 class="hero-title">{{ __('label.users') }}</h1>
            <p class="hero-sub">Manage system users, credentials, role assignments, and active account statuses.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.users.list') }}" class="btn btn--ghost">
                Switch to Ajax List
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
                        <input id="userSearchInput" class="input" type="search" placeholder="Search users by name, username, email...">
                    </div>
                    <span class="badge primary" id="userCountBadge">{{ count($users) }} users</span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Users Only</span>
                </div>
            </div>

            <div class="table-scroll-container">
                <table class="data-table" id="usersTable">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="selectAllCheckbox"><span class="box"></span></label>
                            </th>
                            <th>{{ __('label.name') }}</th>
                            <th>{{ __('form.username') }}</th>
                            <th>{{ __('label.role') ?? 'Role' }}</th>
                            <th>{{ __('label.status') }}</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            @php
                                $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
                                $avatarClass = 'ma-' . (($index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-username="{{ strtolower($user->username ?? '') }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ $avatarClass }}">{{ $initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.users.show', $user->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ $user->name }}
                                                </a>
                                            </div>
                                            <div class="data-cell-user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge info">{{ $user->username ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="badge primary">{{ $user->role ? $user->role->name : '-' }}</span>
                                </td>
                                <td>
                                    @if($user->is_active)
                                         <span class="badge success dot">{{ __('label.active') }}</span>
                                    @else
                                         <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                        {{ btn_detail(route('admin.users.show', $user->id), 'View details') }}
                                        {{ btn_edit(route('admin.users.edit', $user->id), 'Edit user') }}
                                        {{ btn_delete(route('admin.users.destroy', $user->id), null, 'Delete user', null, $user->name ?? $user->username ?? 'this user') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;padding:32px;color:var(--t-light);">
                                    {{ __('message.not_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span>Showing <strong style="color:var(--t-base)">{{ count($users) }}</strong> entries</span>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('userSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#usersTable tbody tr.data-row');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    function filterTable() {
        const query = (searchInput?.value || '').toLowerCase().trim();

        tableRows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const username = row.getAttribute('data-username') || '';

            const matchesSearch = !query || name.includes(query) || email.includes(query) || username.includes(query);

            row.style.display = matchesSearch ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
                const row = cb.closest('tr');
                if (row) {
                    row.classList.toggle('is-selected', selectAllCheckbox.checked);
                }
            });
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const row = cb.closest('tr');
            if (row) {
                row.classList.toggle('is-selected', cb.checked);
            }
        });
    });
});
</script>
@endsection
