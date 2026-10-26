@extends('admin.layouts.theme', [
    'title' => 'Edit role',
    'activeMenu' => 'roles',
    'breadcrumbsText' => 'Workspace | roles | Edit role'
])

@section('css')
<style>
/* Module Treeview in Role Form */
.module-treeview-card {
    background: var(--card-bg, #ffffff);
    border: 1px solid var(--border, #e2e8f0);
    border-radius: var(--radius, 12px);
    overflow: hidden;
    margin-top: 24px;
}

.tree-view-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
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
    padding: 8px 14px;
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
    width: 30px;
    height: 30px;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.module-node-info {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.module-node-title {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.module-node-name {
    color: var(--t-base, #1e293b);
    font-weight: 600;
    font-size: 13.5px;
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

/* Permissions group with toggles */
.module-perms-group {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.perm-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 6px;
    border: 1px solid var(--border, #e2e8f0);
    background: var(--bg-surface, #f8fafc);
    font-size: 11.5px;
    font-weight: 600;
    color: var(--t-muted, #64748b);
    cursor: pointer;
    user-select: none;
    transition: all 0.15s ease;
}

.perm-pill input[type="checkbox"] {
    cursor: pointer;
    margin: 0;
    width: 14px;
    height: 14px;
    accent-color: var(--primary, #3b82f6);
}

.perm-pill:hover {
    border-color: var(--primary, #3b82f6);
    background: #ffffff;
}

.perm-pill:has(input:checked) {
    background: rgba(59, 130, 246, 0.08);
    border-color: var(--primary, #3b82f6);
    color: var(--primary, #2563eb);
}

.perm-pill.read-pill:has(input:checked) {
    background: rgba(16, 185, 129, 0.1);
    border-color: #10b981;
    color: #047857;
}

.perm-pill.read-pill input:checked {
    accent-color: #10b981;
}

.perm-pill.delete-pill:has(input:checked) {
    background: rgba(239, 68, 68, 0.08);
    border-color: #ef4444;
    color: #dc2626;
}

.perm-pill.delete-pill input:checked {
    accent-color: #ef4444;
}

/* Quick tree actions toolbar */
.tree-controls-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    background: var(--bg-surface, #f8fafc);
    border-bottom: 1px solid var(--border, #e2e8f0);
    gap: 12px;
    flex-wrap: wrap;
}

.btn-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 6px;
    border: 1px solid var(--border, #cbd5e1);
    background: #ffffff;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--t-muted, #475569);
    cursor: pointer;
    transition: all 0.15s ease;
}

.btn-pill:hover {
    background: var(--bg-surface, #f1f5f9);
    border-color: var(--primary, #3b82f6);
    color: var(--primary, #3b82f6);
}

.btn-check-children {
    display: inline-flex;
    align-items: center;
    padding: 3px 7px;
    font-size: 10.5px;
    font-weight: 600;
    border-radius: 4px;
    border: 1px solid var(--border, #cbd5e1);
    background: #ffffff;
    color: var(--t-muted, #475569);
    cursor: pointer;
    line-height: 1.2;
    transition: all 0.15s ease;
}

.btn-check-children:hover {
    background: var(--bg-surface, #f1f5f9);
    border-color: var(--primary, #3b82f6);
    color: var(--primary, #3b82f6);
}
</style>
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · roles</span>
            <h1 class="hero-title">Edit role</h1>
            <p class="hero-sub">Update record details for <strong>{{ $item->name ?? 'Record' }}</strong> and configure module permissions.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.roles.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="editForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

    @if ($errors->any())
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
            <div class="body">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Information</span>
                    <h2 class="card-title">Edit role</h2>
                </div>
                <span class="badge {{ $item->is_active ? 'success dot' : 'dot' }}">
                    {{ $item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.roles.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label class="field-label" for="code">
                            Code <span class="req">*</span>
                        </label>
                        <input id="code" name="code" class="input @error('code') is-invalid @enderror" type="text" value="{{ old('code', $item->code) }}" required>
                        @error('code')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="name">
                            Name <span class="req">*</span>
                        </label>
                        <input id="name" name="name" class="input @error('name') is-invalid @enderror" type="text" value="{{ old('name', $item->name) }}" required>
                        @error('name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="is_verifier">
                            Is Verifier <span class="req">*</span>
                        </label>
                        <input id="is_verifier" name="is_verifier" class="input @error('is_verifier') is-invalid @enderror" type="text" value="{{ old('is_verifier', $item->is_verifier ? '1' : '0') }}" required>
                        @error('is_verifier')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label class="field-label" for="order_number">
                            Order Number <span class="req">*</span>
                        </label>
                        <input id="order_number" name="order_number" class="input @error('order_number') is-invalid @enderror" type="number" value="{{ old('order_number', $item->order_number) }}" required>
                        @error('order_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- MODULE ACCESS TREEVIEW -->
                <div class="module-treeview-card">
                    <div class="tree-controls-bar">
                        <div style="font-weight: 700; font-size: 13px; color: var(--t-base);">
                            Module Permissions (t_module_access)
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button type="button" class="btn-pill" id="expandAllBtn" title="Expand all tree nodes">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 11 12 6 17 11"/><polyline points="7 18 12 13 17 18"/></svg>
                                Expand All
                            </button>
                            <button type="button" class="btn-pill" id="collapseAllBtn" title="Collapse all tree nodes">
                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="7 13 12 18 17 13"/><polyline points="7 6 12 11 17 6"/></svg>
                                Collapse All
                            </button>
                            <button type="button" class="btn-pill" id="checkAllReadBtn" title="Grant Read to all modules">
                                Check All Read
                            </button>
                            <button type="button" class="btn-pill" id="uncheckAllBtn" title="Clear all permissions">
                                Clear All
                            </button>
                        </div>
                    </div>

                    <div class="tree-view-header">
                        <div>Module Structure</div>
                        <div style="display:flex;gap:24px;padding-right:8px;">
                            <span>Permissions (Read · Create · Update · Delete)</span>
                        </div>
                    </div>

                    @if(!empty($moduleTree) && count($moduleTree) > 0)
                        <ul class="module-tree-root" id="moduleTreeRoot">
                            @foreach($moduleTree as $rootModule)
                                @include('admin.sections.roles._module_tree_item', [
                                    'module'         => $rootModule,
                                    'depth'          => 0,
                                    'existingAccess' => $existingAccess ?? null,
                                ])
                            @endforeach
                        </ul>
                    @else
                        <div style="text-align:center;padding:32px;color:var(--t-light);">
                            No modules found.
                        </div>
                    @endif
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const treeRoot = document.getElementById('moduleTreeRoot');
    if (!treeRoot) return;

    // Toggle collapse/expand node
    treeRoot.querySelectorAll('.btn-tree-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const node = btn.closest('.module-tree-node');
            if (node) {
                node.classList.toggle('collapsed');
            }
        });
    });

    // Expand all / Collapse all buttons
    const expandAllBtn = document.getElementById('expandAllBtn');
    const collapseAllBtn = document.getElementById('collapseAllBtn');

    if (expandAllBtn) {
        expandAllBtn.addEventListener('click', function () {
            treeRoot.querySelectorAll('.module-tree-node.has-children').forEach(n => n.classList.remove('collapsed'));
        });
    }

    if (collapseAllBtn) {
        collapseAllBtn.addEventListener('click', function () {
            treeRoot.querySelectorAll('.module-tree-node.has-children').forEach(n => n.classList.add('collapsed'));
        });
    }

    // Check all Read / Clear all
    const checkAllReadBtn = document.getElementById('checkAllReadBtn');
    const uncheckAllBtn = document.getElementById('uncheckAllBtn');

    if (checkAllReadBtn) {
        checkAllReadBtn.addEventListener('click', function () {
            treeRoot.querySelectorAll('.perm-checkbox.perm-read').forEach(cb => {
                cb.checked = true;
            });
        });
    }

    if (uncheckAllBtn) {
        uncheckAllBtn.addEventListener('click', function () {
            treeRoot.querySelectorAll('.perm-checkbox').forEach(cb => {
                cb.checked = false;
            });
        });
    }

    /**
     * Recursively cascade checking can_read to parent nodes.
     * When a child's can_read is checked, the parent menu's can_read must also be checked.
     */
    function cascadeParentCanRead(childNode) {
        const parentList = childNode.parentElement;
        if (!parentList || !parentList.classList.contains('module-tree-children')) {
            return;
        }

        const parentNode = parentList.closest('.module-tree-node');
        if (!parentNode) {
            return;
        }

        const parentRow = parentNode.querySelector(':scope > .module-node-row');
        if (parentRow) {
            const parentReadCheckbox = parentRow.querySelector('.perm-checkbox.perm-read');
            if (parentReadCheckbox && !parentReadCheckbox.checked) {
                parentReadCheckbox.checked = true;
                cascadeParentCanRead(parentNode);
            }
        }
    }

    // Handle perm checkbox changes
    treeRoot.addEventListener('change', function (e) {
        const target = e.target;
        if (!target.classList.contains('perm-checkbox')) return;

        const node = target.closest('.module-tree-node');
        if (!node) return;

        // If create, update, or delete is checked, automatically check can_read on the same node
        if (target.checked && (target.classList.contains('perm-create') || target.classList.contains('perm-update') || target.classList.contains('perm-delete'))) {
            const nodeRow = target.closest('.module-node-row');
            if (nodeRow) {
                const readCb = nodeRow.querySelector('.perm-checkbox.perm-read');
                if (readCb && !readCb.checked) {
                    readCb.checked = true;
                    cascadeParentCanRead(node);
                }
            }
        }

        // If can_read was checked, cascade to parent modules
        if (target.classList.contains('perm-read') && target.checked) {
            cascadeParentCanRead(node);
        }
    });

    // Parent buttons to batch check/clear permissions for self and all children
    treeRoot.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-check-children');
        if (!btn) return;
        e.preventDefault();

        const parentNode = btn.closest('.module-tree-node');
        if (!parentNode) return;

        const action = btn.getAttribute('data-action');
        const checkboxes = parentNode.querySelectorAll('.perm-checkbox');

        if (action === 'all') {
            // Check all read, create, update, delete for parent and all descendant nodes
            checkboxes.forEach(cb => {
                cb.checked = true;
            });
            // Ensure ancestors above this parent also have can_read checked
            cascadeParentCanRead(parentNode);
        } else if (action === 'read') {
            // Check only read for parent and all descendant nodes
            parentNode.querySelectorAll('.perm-checkbox.perm-read').forEach(cb => {
                cb.checked = true;
            });
            cascadeParentCanRead(parentNode);
        } else if (action === 'clear') {
            // Clear all permissions in this subtree
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
        }
    });
});
</script>
@endsection
