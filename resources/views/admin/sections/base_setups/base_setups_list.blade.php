@extends('admin.layouts.theme', [
    'title' => 'Base Setups',
    'activeMenu' => 'base_setups',
    'breadcrumbsText' => 'Workspace | Base Setups'
])

@section('css')
<style>
    /* Fixed Table Columns (Checkbox, Title, Actions) */
    .table-scroll-container {
        overflow-x: auto;
        margin: 0 -22px;
        position: relative;
    }

    #dataTable {
        margin: 0 22px;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Fixed Checkbox column (1st column) */
    #dataTable th:nth-child(1),
    #dataTable td:nth-child(1) {
        position: sticky;
        left: 0;
        z-index: 2;
        background: var(--bg-card);
        width: 44px;
        min-width: 44px;
    }

    /* Fixed Title column (2nd column) */
    #dataTable th:nth-child(2),
    #dataTable td:nth-child(2) {
        position: sticky;
        left: 44px;
        z-index: 2;
        background: var(--bg-card);
        min-width: 220px;
        box-shadow: 2px 0 6px -2px rgba(0, 0, 0, 0.08);
    }

    /* Fixed Actions column (last column) */
    #dataTable th:last-child,
    #dataTable td:last-child {
        position: sticky;
        right: 0;
        z-index: 2;
        background: var(--bg-card);
        min-width: 140px;
        box-shadow: -2px 0 6px -2px rgba(0, 0, 0, 0.08);
    }

    /* Keep header cells above scrolling body cells */
    #dataTable thead th:nth-child(1),
    #dataTable thead th:nth-child(2),
    #dataTable thead th:last-child {
        z-index: 5;
    }

    /* Preserve hover & selected row backgrounds on sticky columns */
    #dataTable tbody tr:hover td:nth-child(1),
    #dataTable tbody tr:hover td:nth-child(2),
    #dataTable tbody tr:hover td:last-child {
        background: var(--bg-hover) !important;
    }

    #dataTable tbody tr.is-selected td:nth-child(1),
    #dataTable tbody tr.is-selected td:nth-child(2),
    #dataTable tbody tr.is-selected td:last-child {
        background: var(--primary-soft) !important;
    }
</style>
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Management</span>
            <h1 class="hero-title">Base Setups</h1>
            <p class="hero-sub">Manage and browse Base Setups records.</p>
        </div>
        <div class="hero-actions">
           
        @if($access->can_create==true)
            <a href="{{ route('admin.base_setups.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Base Setup
            </a>
         @endif   
        
        </div>
    </section>

    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div class="body">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
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
                        <input id="searchInput" class="input" type="search" placeholder="Search Base Setups...">
                    </div>
                    <span class="badge primary">{{ count($items) }} items</span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Records Only</span>
                </div>
            </div>

            <div class="table-scroll-container">
                <table class="data-table" id="dataTable">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="selectAll"><span class="box"></span></label>
                            </th>
                            <th>Title</th>            
                            <th>Using Dual Lang</th>            
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $initials = strtoupper(substr($item->title ?? 'I', 0, 2));
                                $avatarClass = 'ma-' . (($index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-name="{{ strtolower($item->title ?? '') }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ $avatarClass }}">{{ $initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.base_setups.show', $item->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ $item->title ?? '-' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->using_dual_lang ?? '-' }}</td>
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                       @if($access->can_read)
                                        {{ btn_detail(route('admin.base_setups.show', $item->id), 'View details') }}
                                        @endif

                                        @if($access->can_update)
                                        {{ btn_edit(route('admin.base_setups.edit', $item->id), 'Edit Base Setup') }}
                                        @endif
                                        
                                         @if($access->can_delete)
                                        {{ btn_delete(route('admin.base_setups.destroy', $item->id), null, 'Delete Base Setup', null, $item->title ?? 'Base Setup') }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20" style="text-align:center;padding:32px;color:var(--t-light);">
                                    {{ __('message.not_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span>Showing <strong style="color:var(--t-base)">{{ count($items) }}</strong> entries</span>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#dataTable tbody tr.data-row');
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = (this.value || '').toLowerCase().trim();
            tableRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                row.style.display = (!query || name.includes(query)) ? '' : 'none';
            });
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
                const row = cb.closest('tr');
                if (row) row.classList.toggle('is-selected', selectAllCheckbox.checked);
            });
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const row = cb.closest('tr');
            if (row) row.classList.toggle('is-selected', cb.checked);
        });
    });
});
</script>
@endsection