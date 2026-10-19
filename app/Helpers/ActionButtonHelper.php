<?php

namespace App\Helpers;

use Illuminate\Support\HtmlString;

class ActionButtonHelper
{
    /**
     * Render the Detail / View action button.
     *
     * @param string $url Target URL
     * @param string $title Tooltip and aria-label
     * @param string|null $extraClass Additional CSS classes
     * @return HtmlString
     */
    public static function detail(string $url, string $title = 'View details', ?string $extraClass = null): HtmlString
    {
        $class = 'btn--icon' . ($extraClass ? ' ' . e($extraClass) : '');
        $safeUrl = e($url);
        $safeTitle = e($title);

        $html = <<<HTML
<a href="{$safeUrl}" class="{$class}" title="{$safeTitle}" aria-label="{$safeTitle}">
    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
</a>
HTML;

        return new HtmlString($html);
    }

    /**
     * Render the Edit action button.
     *
     * @param string $url Target URL
     * @param string $title Tooltip and aria-label
     * @param string|null $extraClass Additional CSS classes
     * @return HtmlString
     */
    public static function edit(string $url, string $title = 'Edit', ?string $extraClass = null): HtmlString
    {
        $class = 'btn--icon' . ($extraClass ? ' ' . e($extraClass) : '');
        $safeUrl = e($url);
        $safeTitle = e($title);

        $html = <<<HTML
<a href="{$safeUrl}" class="{$class}" title="{$safeTitle}" aria-label="{$safeTitle}">
    <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
</a>
HTML;

        return new HtmlString($html);
    }

    /**
     * Render the Delete action button with its form and confirmation modal/prompt.
     *
     * @param string $url Target form action URL
     * @param string $confirmMessage Confirmation message
     * @param string $title Tooltip and aria-label
     * @param string|null $extraClass Additional CSS classes
     * @return HtmlString
     */
    public static function delete(string $url, string $confirmMessage = 'Are you sure you want to delete this record?', string $title = 'Delete', ?string $extraClass = null): HtmlString
    {
        $class = 'btn--icon' . ($extraClass ? ' ' . e($extraClass) : '');
        $safeUrl = e($url);
        $safeTitle = e($title);
        $escapedConfirm = addslashes(htmlspecialchars($confirmMessage, ENT_QUOTES, 'UTF-8'));
        $csrfToken = csrf_token();

        $html = <<<HTML
<form action="{$safeUrl}" method="POST" style="display:inline;" onsubmit="return confirm('{$escapedConfirm}');">
    <input type="hidden" name="_token" value="{$csrfToken}">
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit" class="{$class}" title="{$safeTitle}" aria-label="{$safeTitle}" style="color:var(--danger, #ef4444);">
        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
    </button>
</form>
HTML;

        return new HtmlString($html);
    }

    /**
     * Render a combined action group containing view/detail, edit, and/or delete.
     *
     * @param string|null $editUrl
     * @param string|null $deleteUrl
     * @param string|null $detailUrl
     * @param array $options
     * @return HtmlString
     */
    public static function actions(?string $editUrl = null, ?string $deleteUrl = null, ?string $detailUrl = null, array $options = []): HtmlString
    {
        $buttons = [];

        if ($detailUrl) {
            $buttons[] = self::detail($detailUrl, $options['detail_title'] ?? 'View details');
        }

        if ($editUrl) {
            $buttons[] = self::edit($editUrl, $options['edit_title'] ?? 'Edit');
        }

        if ($deleteUrl) {
            $buttons[] = self::delete($deleteUrl, $options['confirm_delete'] ?? 'Are you sure you want to delete this record?', $options['delete_title'] ?? 'Delete');
        }

        $html = '<div class="data-cell-actions" style="justify-content: flex-end;">' . implode(' ', $buttons) . '</div>';

        return new HtmlString($html);
    }
}
