@php
    $depth = $depth ?? 0;
    $hasChildren = $module->children && $module->children->count() > 0;
    $initials = strtoupper(substr($module->label ?? $module->name ?? 'M', 0, 2));
    $colorIndex = (crc32($module->name ?? 'M') % 6) + 1;
    if ($colorIndex < 1) $colorIndex = 1;
    $avatarClass = 'ma-' . $colorIndex;
@endphp

<li class="module-tree-node {{ $hasChildren ? 'has-children' : 'is-leaf' }}" data-node-id="{{ $module->id }}" data-search-text="{{ strtolower(($module->name ?? '') . ' ' . ($module->label ?? '') . ' ' . ($module->path ?? '')) }}">
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
                    <a href="{{ route('admin.modules.show', $module->id) }}" class="module-node-name">
                        {{ $module->label ?? $module->name }}
                    </a>
                    @if(!empty($module->name) && $module->name !== ($module->label ?? ''))
                        <span class="module-code-badge">{{ $module->name }}</span>
                    @endif
                    @if($hasChildren)
                        <span class="badge primary count-badge">{{ $module->children->count() }} sub</span>
                    @endif
                </div>
                <div class="module-node-submeta">
                    @if(!empty($module->path))
                        <span class="meta-tag path-tag">
                            <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                            {{ $module->path }}
                        </span>
                    @endif
                    @if(!empty($module->resource_class))
                        <span class="meta-tag class-tag" title="Resource Class">
                            <svg viewBox="0 0 24 24"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            {{ class_basename($module->resource_class) }}
                        </span>
                    @endif
                    @if(isset($module->sort_number))
                        <span class="meta-tag sort-tag" title="Sort Order">
                            #{{ $module->sort_number }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="module-node-badges">
            @if($module->is_menubar)
                <span class="badge neutral" title="Visible in Menubar">
                    <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:3px"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    Menu
                </span>
            @endif
            @if($module->is_active)
                <span class="badge success dot">Active</span>
            @else
                <span class="badge danger dot">Inactive</span>
            @endif
        </div>

        <div class="module-node-actions">
            {{ btn_detail(route('admin.modules.show', $module->id), 'View details') }}
            {{ btn_edit(route('admin.modules.edit', $module->id), 'Edit Module') }}
            {{ btn_delete(route('admin.modules.destroy', $module->id), null, 'Delete Module', null, $module->label ?? $module->name ?? 'Module') }}
        </div>
    </div>

    @if($hasChildren)
        <ul class="module-tree-children">
            @foreach($module->children as $child)
                @include('admin.sections.modules._tree_item', ['module' => $child, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>
