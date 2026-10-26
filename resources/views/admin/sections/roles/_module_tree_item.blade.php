@php
    $depth = $depth ?? 0;
    $hasChildren = $module->children && $module->children->count() > 0;
    $initials = strtoupper(substr($module->label ?? $module->name ?? 'M', 0, 2));
    $colorIndex = (crc32($module->name ?? 'M') % 6) + 1;
    if ($colorIndex < 1) $colorIndex = 1;
    $avatarClass = 'ma-' . $colorIndex;

    $moduleId = $module->id;
    $currentAccess = isset($existingAccess) && isset($existingAccess[$moduleId]) ? $existingAccess[$moduleId] : null;

    $oldModuleInput = old("modules.{$moduleId}");
    if ($oldModuleInput !== null) {
        $canRead   = !empty($oldModuleInput['can_read']);
        $canCreate = !empty($oldModuleInput['can_create']);
        $canUpdate = !empty($oldModuleInput['can_update']);
        $canDelete = !empty($oldModuleInput['can_delete']);
    } elseif ($currentAccess) {
        $canRead   = (bool) $currentAccess->can_read;
        $canCreate = (bool) $currentAccess->can_create;
        $canUpdate = (bool) $currentAccess->can_update;
        $canDelete = (bool) $currentAccess->can_delete;
    } else {
        $canRead = false;
        $canCreate = false;
        $canUpdate = false;
        $canDelete = false;
    }
@endphp

<li class="module-tree-node {{ $hasChildren ? 'has-children' : 'is-leaf' }}" data-node-id="{{ $moduleId }}" data-parent-id="{{ $module->parent_id ?? '' }}">
    <div class="module-node-row">
        <div class="module-node-main">
            @if($hasChildren)
                <button type="button" class="btn-tree-toggle" aria-label="Toggle submodules" title="Toggle submodules">
                    <svg class="tree-icon-chevron" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            @else
                <span class="tree-spacer-dot">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle></svg>
                </span>
            @endif

            <div class="av {{ $avatarClass }} tree-av">{{ $initials }}</div>

            <div class="module-node-info">
                <div class="module-node-title">
                    <span class="module-node-name">{{ $module->label ?? $module->name }}</span>
                    @if(!empty($module->name) && $module->name !== ($module->label ?? ''))
                        <span class="module-code-badge">{{ $module->name }}</span>
                    @endif
                    @if($hasChildren)
                        <span class="badge primary count-badge">{{ $module->children->count() }} sub</span>
                    @endif
                </div>
                @if(!empty($module->path))
                    <div class="module-node-submeta">
                        <span class="meta-tag path-tag">
                            <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            {{ $module->path }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="module-perms-group">
            @if($hasChildren)
                <div class="parent-cascade-tools" style="display:inline-flex;align-items:center;gap:4px;margin-right:6px;padding-right:8px;border-right:1px solid var(--border,#e2e8f0);">
                    <button type="button" class="btn-check-children" data-action="all" title="Check all permissions for this module and its submodules">
                        Check Sub All
                    </button>
                    <button type="button" class="btn-check-children" data-action="read" title="Check Read for this module and its submodules">
                        Sub Read
                    </button>
                    <button type="button" class="btn-check-children" data-action="clear" title="Clear all permissions for this module and its submodules">
                        Sub Clear
                    </button>
                </div>
            @endif

            <label class="perm-pill read-pill" title="Read permission">
                <input type="checkbox"
                       name="modules[{{ $moduleId }}][can_read]"
                       value="1"
                       class="perm-checkbox perm-read"
                       data-module-id="{{ $moduleId }}"
                       data-parent-id="{{ $module->parent_id ?? '' }}"
                       {{ $canRead ? 'checked' : '' }}>
                <span class="perm-label">Read</span>
            </label>

            <label class="perm-pill create-pill" title="Create permission">
                <input type="checkbox"
                       name="modules[{{ $moduleId }}][can_create]"
                       value="1"
                       class="perm-checkbox perm-create"
                       data-module-id="{{ $moduleId }}"
                       data-parent-id="{{ $module->parent_id ?? '' }}"
                       {{ $canCreate ? 'checked' : '' }}>
                <span class="perm-label">Create</span>
            </label>

            <label class="perm-pill update-pill" title="Update permission">
                <input type="checkbox"
                       name="modules[{{ $moduleId }}][can_update]"
                       value="1"
                       class="perm-checkbox perm-update"
                       data-module-id="{{ $moduleId }}"
                       data-parent-id="{{ $module->parent_id ?? '' }}"
                       {{ $canUpdate ? 'checked' : '' }}>
                <span class="perm-label">Update</span>
            </label>

            <label class="perm-pill delete-pill" title="Delete permission">
                <input type="checkbox"
                       name="modules[{{ $moduleId }}][can_delete]"
                       value="1"
                       class="perm-checkbox perm-delete"
                       data-module-id="{{ $moduleId }}"
                       data-parent-id="{{ $module->parent_id ?? '' }}"
                       {{ $canDelete ? 'checked' : '' }}>
                <span class="perm-label">Delete</span>
            </label>
        </div>
    </div>

    @if($hasChildren)
        <ul class="module-tree-children">
            @foreach($module->children as $child)
                @include('admin.sections.roles._module_tree_item', [
                    'module'         => $child,
                    'depth'          => $depth + 1,
                    'existingAccess' => $existingAccess ?? null,
                ])
            @endforeach
        </ul>
    @endif
</li>
