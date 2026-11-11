@extends('admin.layouts.theme', [
    'title' => 'Content_dtls',
    'activeMenu' => 'content_dtls',
    'breadcrumbsText' => 'Workspace | Content_dtls'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Management</span>
            <h1 class="hero-title">Content_dtls</h1>
            <p class="hero-sub">Manage and browse Content_dtls records.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.content_dtls.list') }}" class="btn btn--ghost">
                Switch to Ajax List
            </a>
            <a href="{{ route('admin.content_dtls.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Content_dtl
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
                        <input id="searchInput" class="input" type="search" placeholder="Search Content_dtls...">
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
                            <th>T Content Id</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Description</th>
                            <th>Sort Number</th>
                            <th>Image</th>
                            <th>Background</th>
                            <th>Icon</th>
                            <th>Redirect To</th>
                            <th>File</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $initials = strtoupper(substr($item->t_content_id ?? 'I', 0, 2));
                                $avatarClass = 'ma-' . (($index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-name="{{ strtolower($item->t_content_id ?? '') }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ $avatarClass }}">{{ $initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.content_dtls.show', $item->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ $item->t_content_id ?? '-' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->title ?? '-' }}</td>
                                <td>{{ $item->subtitle ?? '-' }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>{{ $item->sort_number ?? '-' }}</td>
                                <td>{{ $item->image ?? '-' }}</td>
                                <td>{{ $item->background ?? '-' }}</td>
                                <td>{{ $item->icon ?? '-' }}</td>
                                <td>{{ $item->redirect_to ?? '-' }}</td>
                                <td>{{ $item->file ?? '-' }}</td>
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                        {{ btn_detail(route('admin.content_dtls.show', $item->id), 'View details') }}
                                        {{ btn_edit(route('admin.content_dtls.edit', $item->id), 'Edit Content_dtl') }}
                                        {{ btn_delete(route('admin.content_dtls.destroy', $item->id), null, 'Delete Content_dtl', null, $item->t_content_id ?? 'Content_dtl') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" style="text-align:center;padding:32px;color:var(--t-light);">
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