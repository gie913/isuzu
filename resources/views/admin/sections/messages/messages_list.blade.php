@extends('admin.layouts.theme', [
    'title' => 'Messages',
    'activeMenu' => 'messages',
    'breadcrumbsText' => 'Workspace | Messages'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Management</span>
            <h1 class="hero-title">Messages</h1>
            <p class="hero-sub">Manage and browse Messages records.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.messages.list') }}" class="btn btn--ghost">
                Switch to Ajax List
            </a>
            <a href="{{ route('admin.messages.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Message
            </a>
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
                        <input id="searchInput" class="input" type="search" placeholder="Search Messages...">
                    </div>
                    <span class="badge primary">{{ count($items) }} items</span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Records Only</span>
                </div>
            </div>

            <div style="overflow-x:auto;margin:0 -22px">
                <table class="data-table" id="dataTable" style="margin:0 22px;min-width:900px">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="selectAll"><span class="box"></span></label>
                            </th>
                            <th>From User Id</th>
                            <th>To User Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Category</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Is Replied</th>
                            <th>Replied At</th>
                            <th>Read At</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $initials = strtoupper(substr($item->from_user_id ?? 'I', 0, 2));
                                $avatarClass = 'ma-' . (($index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-name="{{ strtolower($item->from_user_id ?? '') }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ $avatarClass }}">{{ $initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.messages.show', $item->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ $item->from_user_id ?? '-' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->to_user_id ?? '-' }}</td>
                                <td>{{ $item->name ?? '-' }}</td>
                                <td>{{ $item->phone ?? '-' }}</td>
                                <td>{{ $item->email ?? '-' }}</td>
                                <td>{{ $item->address ?? '-' }}</td>
                                <td>{{ $item->category ?? '-' }}</td>
                                <td>{{ $item->message ?? '-' }}</td>
                                <td>{{ $item->status ?? '-' }}</td>
                                <td>{{ $item->is_replied ?? '-' }}</td>
                                <td>{{ $item->replied_at ?? '-' }}</td>
                                <td>{{ $item->read_at ?? '-' }}</td>
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                        {{ btn_detail(route('admin.messages.show', $item->id), 'View details') }}
                                        {{ btn_edit(route('admin.messages.edit', $item->id), 'Edit Message') }}
                                        {{ btn_delete(route('admin.messages.destroy', $item->id), null, 'Delete Message', null, $item->from_user_id ?? 'Message') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" style="text-align:center;padding:32px;color:var(--t-light);">
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