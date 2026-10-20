@extends('admin.layouts.theme', [
    'title' => 'Modules',
    'activeMenu' => 'modules',
    'breadcrumbsText' => 'Workspace | Modules'
])

@section('css')
<style>
/* Module Treeview Modern UI Styling */
.module-treeview-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: var(--radius, 12px);
    overflow: hidden;
}

.tree-view-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: var(--bg-surface, #f8fafc);
    border-bottom: 1px solid var(--border, #e2e8f0);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--t-light, #64748b);
}

.module-tree-root {
    list-style: none;
    margin: 0;
    padding: 12px 16px;
}

.module-tree-children {
    list-style: none;
    margin: 0 0 0 24px;
    padding: 0 0 0 16px;
    border-left: 2px dashed var(--border, #e2e8f0);
    position: relative;
    transition: all 0.2s ease;
}

.module-tree-node {
    margin: 6px 0;
    position: relative;
}

.module-node-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: 8px;
    transition: all 0.15s ease-in-out;
    gap: 16px;
}

.module-node-row:hover {
    border-color: var(--primary, #3b82f6);
    background: var(--bg-hover, rgba(59, 130, 246, 0.02));
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
}

.module-node-main {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

.btn-tree-toggle {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    border: 1px solid var(--border, #cbd5e1);
    background: var(--card-bg, #ffffff);
    color: var(--t-muted, #475569);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.btn-tree-toggle:hover {
    background: var(--primary, #3b82f6);
    color: #ffffff;
    border-color: var(--primary, #3b82f6);
}

.tree-icon-chevron {
    width: 14px;
    height: 14px;
    stroke: currentColor;
    stroke-width: 2.2;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    transition: transform 0.2s ease;
    transform: rotate(90deg);
}

.module-tree-node.collapsed > .module-node-row .tree-icon-chevron {
    transform: rotate(0deg);
}

.module-tree-node.collapsed > .module-tree-children {
    display: none;
}

.tree-spacer-dot {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--t-light, #94a3b8);
}

.tree-spacer-dot svg {
    width: 8px;
    height: 8px;
    fill: currentColor;
}

.tree-av {
    width: 32px;
    height: 32px;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.module-node-info {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.module-node-title {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.module-node-name {
    color: var(--t-base, #1e293b);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
}

.module-node-name:hover {
    color: var(--primary, #3b82f6);
}

.module-code-badge {
    font-family: var(--font-mono, monospace);
    font-size: 11px;
    color: var(--t-muted, #64748b);
    background: var(--bg-surface, #f1f5f9);
    padding: 2px 7px;
    border-radius: 4px;
}

.count-badge {
    font-size: 10.5px;
    padding: 2px 7px;
    font-weight: 600;
}

.module-node-submeta {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 11.5px;
    color: var(--t-light, #64748b);
    flex-wrap: wrap;
}

.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.meta-tag svg {
    width: 12px;
    height: 12px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.path-tag {
    font-family: var(--font-mono, monospace);
    color: var(--t-muted, #475569);
}

.module-node-badges {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.module-node-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}

/* Tab button controls */
.view-tabs {
    display: inline-flex;
    background: var(--bg-surface, #f1f5f9);
    padding: 3px;
    border-radius: 8px;
    border: 1px solid var(--border, #e2e8f0);
}

.view-tab-btn {
    border: none;
    background: transparent;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--t-muted, #64748b);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}

.view-tab-btn.is-active {
    background: var(--card-bg, #ffffff);
    color: var(--primary, #3b82f6);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.view-tab-btn svg {
    width: 14px;
    height: 14px;
    fill: none;
    stroke: currentColor;
    stroke-width: 2;
}

.tree-quick-controls {
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn-pill {
    background: var(--bg-surface, #f8fafc);
    border: 1px solid var(--border, #e2e8f0);
    padding: 4px 10px;
    font-size: 11.5px;
    border-radius: 6px;
    color: var(--t-muted, #64748b);
    cursor: pointer;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-pill:hover {
    background: var(--card-bg, #ffffff);
    color: var(--primary, #3b82f6);
    border-color: var(--primary, #3b82f6);
}
</style>
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · System Architecture</span>
            <h1 class="hero-title">Modules Hierarchy</h1>
            <p class="hero-sub">Manage and explore application modules with parent-child relationship treeview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.modules.list') }}" class="btn btn--ghost">
                Switch to Ajax Datatable
            </a>
            <a href="{{ route('admin.modules.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Module
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
        <section class="col-12 card module-treeview-card">
            <div class="data-toolbar">
                <div class="data-toolbar-left" style="gap: 12px; flex-wrap: wrap;">
                    <div class="input-icon" style="flex:1;max-width:320px">
                        <span class="ico">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input id="searchInput" class="input" type="search" placeholder="Search modules by name, label or path...">
                    </div>

                    <div class="view-tabs">
                        <button type="button" class="view-tab-btn is-active" id="tabTreeBtn" title="Treeview layout">
                            <svg viewBox="0 0 24 24"><path d="M6 3v12"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                            Treeview
                        </button>
                        <button type="button" class="view-tab-btn" id="tabTableBtn" title="Flat table layout">
                            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>
                            Flat Table
                        </button>
                    </div>

                    <span class="badge primary">{{ count($items) }} total modules</span>
                </div>

                <div class="data-toolbar-right" style="gap: 10px;">
                    <div class="tree-quick-controls" id="treeControls">
                        <button type="button" class="btn-pill" id="expandAllBtn" title="Expand all tree nodes">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 11 12 6 17 11"/><polyline points="7 18 12 13 17 18"/></svg>
                            Expand All
                        </button>
                        <button type="button" class="btn-pill" id="collapseAllBtn" title="Collapse all tree nodes">
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 13 12 18 17 13"/><polyline points="7 6 12 11 17 6"/></svg>
                            Collapse All
                        </button>
                    </div>
                </div>
            </div>

            <!-- 1. TREEVIEW CONTAINER -->
            <div id="treeViewContainer" style="display:block;">
                <div class="tree-view-header">
                    <div>Module Structure & Hierarchy</div>
                    <div style="display:flex;gap:36px;padding-right:12px;">
                        <span>Status</span>
                        <span style="min-width:90px;text-align:right;">Actions</span>
                    </div>
                </div>

                @if(!empty($treeItems) && count($treeItems) > 0)
                    <ul class="module-tree-root" id="moduleTreeRoot">
                        @foreach($treeItems as $rootModule)
                            @include('admin.sections.modules._tree_item', ['module' => $rootModule, 'depth' => 0])
                        @endforeach
                    </ul>
                @else
                    <div style="text-align:center;padding:48px;color:var(--t-light);">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px;opacity:0.6;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
                        <div>{{ __('message.not_found') }}</div>
                    </div>
                @endif
            </div>

            <!-- 2. FLAT DATATABLE CONTAINER -->
            <div id="tableViewContainer" style="display:none;overflow-x:auto;margin:0 -22px">
                <table class="data-table" id="dataTable" style="margin:0 22px;min-width:900px">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="selectAll"><span class="box"></span></label>
                            </th>
                            <th>{{ __('label.name') }}</th>
                            <th>Label / Path</th>
                            <th>Parent Module</th>
                            <th>Menubar</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $initials = strtoupper(substr($item->label ?? $item->name ?? 'M', 0, 2));
                                $avatarClass = 'ma-' . (($index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-search="{{ strtolower(($item->name ?? '') . ' ' . ($item->label ?? '') . ' ' . ($item->path ?? '')) }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ $avatarClass }}">{{ $initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.modules.show', $item->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ $item->name }}
                                                </a>
                                            </div>
                                            <div class="data-cell-user-sub">#{{ $item->sort_number ?? 0 }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $item->label ?? '-' }}</strong>
                                    @if(!empty($item->path))
                                        <div style="font-family:var(--font-mono, monospace);font-size:11.5px;color:var(--t-light);">{{ $item->path }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->parent)
                                        <span class="badge neutral">{{ $item->parent->label ?? $item->parent->name }}</span>
                                    @else
                                        <span style="color:var(--t-light);font-size:12px;">Root</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->is_menubar)
                                        <span class="badge success dot">Visible</span>
                                    @else
                                        <span class="badge neutral">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                        {{ btn_detail(route('admin.modules.show', $item->id), 'View details') }}
                                        {{ btn_edit(route('admin.modules.edit', $item->id), 'Edit Module') }}
                                        {{ btn_delete(route('admin.modules.destroy', $item->id), null, 'Delete Module', null, $item->label ?? $item->name ?? 'Module') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:32px;color:var(--t-light);">
                                    {{ __('message.not_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span>Showing <strong style="color:var(--t-base)">{{ count($treeItems) }}</strong> root modules (<strong style="color:var(--t-base)">{{ count($items) }}</strong> total)</span>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabTreeBtn = document.getElementById('tabTreeBtn');
    const tabTableBtn = document.getElementById('tabTableBtn');
    const treeViewContainer = document.getElementById('treeViewContainer');
    const tableViewContainer = document.getElementById('tableViewContainer');
    const treeControls = document.getElementById('treeControls');
    const searchInput = document.getElementById('searchInput');
    const moduleTreeRoot = document.getElementById('moduleTreeRoot');
    const expandAllBtn = document.getElementById('expandAllBtn');
    const collapseAllBtn = document.getElementById('collapseAllBtn');

    // Tab toggle logic
    function setView(view) {
        if (view === 'tree') {
            tabTreeBtn.classList.add('is-active');
            tabTableBtn.classList.remove('is-active');
            treeViewContainer.style.display = 'block';
            tableViewContainer.style.display = 'none';
            if (treeControls) treeControls.style.display = 'flex';
        } else {
            tabTableBtn.classList.add('is-active');
            tabTreeBtn.classList.remove('is-active');
            treeViewContainer.style.display = 'none';
            tableViewContainer.style.display = 'block';
            if (treeControls) treeControls.style.display = 'none';
        }
    }

    if (tabTreeBtn && tabTableBtn) {
        tabTreeBtn.addEventListener('click', () => setView('tree'));
        tabTableBtn.addEventListener('click', () => setView('table'));
    }

    // Tree node toggle click handler (collapsing / expanding)
    if (moduleTreeRoot) {
        moduleTreeRoot.addEventListener('click', function (e) {
            const toggleBtn = e.target.closest('.btn-tree-toggle');
            if (!toggleBtn) return;
            const node = toggleBtn.closest('.module-tree-node');
            if (node) {
                node.classList.toggle('collapsed');
            }
        });
    }

    // Expand all / Collapse all buttons
    if (expandAllBtn) {
        expandAllBtn.addEventListener('click', function () {
            document.querySelectorAll('.module-tree-node.has-children').forEach(node => {
                node.classList.remove('collapsed');
            });
        });
    }

    if (collapseAllBtn) {
        collapseAllBtn.addEventListener('click', function () {
            document.querySelectorAll('.module-tree-node.has-children').forEach(node => {
                node.classList.add('collapsed');
            });
        });
    }

    // Unified Search Filtering for both Treeview and Flat Table
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = (this.value || '').toLowerCase().trim();

            // 1. Filter Tree Nodes
            const treeNodes = document.querySelectorAll('.module-tree-node');
            if (!query) {
                treeNodes.forEach(node => {
                    node.style.display = '';
                    node.classList.remove('collapsed');
                });
            } else {
                treeNodes.forEach(node => {
                    const text = node.getAttribute('data-search-text') || '';
                    const matches = text.includes(query);
                    const childMatches = node.querySelectorAll(`[data-search-text*="${query}"]`).length > 0;

                    if (matches || childMatches) {
                        node.style.display = '';
                        node.classList.remove('collapsed');
                    } else {
                        node.style.display = 'none';
                    }
                });
            }

            // 2. Filter Flat Table Rows
            const tableRows = document.querySelectorAll('#dataTable tbody tr.data-row');
            tableRows.forEach(row => {
                const search = row.getAttribute('data-search') || '';
                row.style.display = (!query || search.includes(query)) ? '' : 'none';
            });
        });
    }

    // Checkbox select all for Flat Table
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

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