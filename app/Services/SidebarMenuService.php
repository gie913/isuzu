<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SidebarMenuService
{
    /**
     * Generate the sidebarmenu.html file in storage/app/private.
     *
     * @param string|null $roleId
     * @param string|null $userId
     * @return string Path to generated file
     */
    public function generate(?string $roleId = null, ?string $userId = null): string
    {
        // Resolve role ID from user ID if user ID is given or if role ID is empty
        if ($userId && empty($roleId)) {
            $user = DB::table('m_user')->where('id', $userId)->first();
            $roleId = $user?->m_role_id;
        }

        // Query specified by requirements (filtering by logged-in role_id, is_active=1, is_menubar=1)
        $rows = [];
        if (!empty($roleId)) {
            $rows = DB::select("
                select m_m.id, m_m.icon, m_m.path, m_m.resource_class, m_m.parent_id, m_m.sort_number, m_m.label
                from t_module_access tma
                inner join m_module m_m on tma.m_module_id = m_m.id
                where tma.m_role_id = ?
                  and m_m.is_active = 1
                  and tma.is_active = 1
                  and m_m.is_menubar = 1
                order by m_m.sort_number
            ", [$roleId]);
        }

        // Organize tree structure
        $parents = [];
        $childrenByParent = [];

        foreach ($rows as $item) {
            if (empty($item->parent_id)) {
                $parents[] = $item;
            } else {
                $childrenByParent[$item->parent_id][] = $item;
            }
        }

        // Build HTML output
        $html = "<!-- Generated dynamic sidebar menu from t_module_access -->\n";
        $html .= "<nav class=\"nav-section\">\n";
        $html .= "    <div class=\"nav-label\">Workspace</div>\n";
        $html .= "    <a class=\"nav-link\" href=\"" . url('/admin/dashboard') . "\">\n";
        $html .= "        <svg viewBox=\"0 0 24 24\"><path d=\"M3 12 12 3l9 9\"></path><path d=\"M5 10v10h14V10\"></path></svg>\n";
        $html .= "        <span>Dashboard</span>\n";
        $html .= "    </a>\n";

        foreach ($parents as $parent) {
            $parentId = $parent->id ?? null;
            $hasChildren = !empty($parentId) && !empty($childrenByParent[$parentId]);
            $parentIconSvg = $this->renderIconSvg($parent->icon ?? '');
            $parentLabel = htmlspecialchars($parent->label ?: 'Menu');

            if ($hasChildren) {
                // Group / dropdown parent item
                $html .= "    <div class=\"nav-item-group\" data-nav-group>\n";
                $html .= "        <a class=\"nav-link\" href=\"javascript:void(0)\" data-nav-toggle>\n";
                $html .= "            {$parentIconSvg}\n";
                $html .= "            <span>{$parentLabel}</span>\n";
                $html .= "            <svg class=\"chev\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"1.8\"><path d=\"m9 18 6-6-6-6\"/></svg>\n";
                $html .= "        </a>\n";
                $html .= "        <div class=\"nav-submenu\">\n";

                foreach ($childrenByParent[$parentId] as $child) {
                    $childUrl = $this->resolveUrl($child->path ?? '#');
                    $childLabel = htmlspecialchars($child->label ?: 'Submenu');
                    $dataLinkPath = htmlspecialchars(ltrim($child->path ?? '', '/'));
                    $html .= "            <a href=\"{$childUrl}\" data-link-path=\"{$dataLinkPath}\">{$childLabel}</a>\n";
                }

                $html .= "        </div>\n";
                $html .= "    </div>\n";
            } else {
                // Single root link
                $linkUrl = $this->resolveUrl($parent->path ?? '#');
                $dataLinkPath = htmlspecialchars(ltrim($parent->path ?? '', '/'));
                $html .= "    <a class=\"nav-link\" href=\"{$linkUrl}\" data-link-path=\"{$dataLinkPath}\">\n";
                $html .= "        {$parentIconSvg}\n";
                $html .= "        <span>{$parentLabel}</span>\n";
                $html .= "    </a>\n";
            }
        }

        $html .= "</nav>\n";

        // Save file in storage/app/private/sidebarmenu.html
        $dir = storage_path('app/private');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        $filePath = $dir . '/sidebarmenu.html';
        File::put($filePath, $html);

        return $filePath;
    }

    /**
     * Resolve a path into an absolute URL.
     */
    protected function resolveUrl(string $path): string
    {
        $trimmed = trim($path);
        if ($trimmed === '' || $trimmed === '#') {
            return '#';
        }
        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        // Relative path, prepend admin prefix
        $clean = ltrim($trimmed, '/');
        return url('/admin/' . $clean);
    }

    /**
     * Render SVG based on icon field in m_module.
     */
    protected function renderIconSvg(?string $icon): string
    {
        $icon = trim((string) $icon);

        switch ($icon) {
            case 'heroicon-o-users':
                return '<svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';

            case 'heroicon-o-user-group':
                return '<svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';

            case 'heroicon-o-wrench-screwdriver':
                return '<svg viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>';

            case 'heroicon-o-square-3-stack-3d':
                return '<svg viewBox="0 0 24 24"><path d="M2 17l10 5 10-5M2 12l10 5 10-5M12 2L2 7l10 5 10-5z"/></svg>';

            case 'heroicon-o-finger-print':
                return '<svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-10 10c0 4.1 2.5 7.6 6 9.2M12 6a6 6 0 0 0-6 6c0 2.8 1.8 5.2 4.3 6.1M12 10a2 2 0 0 0-2 2c0 1.5 1 2.7 2.4 2.9M12 2c5.5 0 10 4.5 10 10 0 3-1.3 5.7-3.4 7.5M16 12a4 4 0 0 1-4 4"/></svg>';

            case 'heroicon-o-newspaper':
                return '<svg viewBox="0 0 24 24"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>';

            case 'heroicon-o-printer':
                return '<svg viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>';

            case 'heroicon-o-adjustments-horizontal':
                return '<svg viewBox="0 0 24 24"><line x1="21" y1="4" x2="14" y2="4"/><line x1="10" y1="4" x2="3" y2="4"/><line x1="21" y1="12" x2="12" y2="12"/><line x1="8" y1="12" x2="3" y2="12"/><line x1="21" y1="20" x2="16" y2="20"/><line x1="12" y1="20" x2="3" y2="20"/><circle cx="12" cy="4" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="14" cy="20" r="2"/></svg>';

            case 'heroicon-o-chat-bubble-bottom-center-text':
                return '<svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="13" y2="13"/></svg>';

            case 'heroicon-o-arrows-right-left':
                return '<svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 5h18"/><polyline points="7 23 3 19 7 15"/><path d="M21 19H3"/></svg>';

            default:
                if (str_starts_with($icon, '<svg') && str_ends_with($icon, '</svg>')) {
                    return $icon;
                }
                return '<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18M3 9h18"/></svg>';
        }
    }
}
