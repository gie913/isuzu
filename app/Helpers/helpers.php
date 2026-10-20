<?php

use App\Helpers\ActionButtonHelper;
use Illuminate\Support\HtmlString;

if (!function_exists('btn_edit')) {
    /**
     * Render theme edit button.
     *
     * @param string $url
     * @param string $title
     * @param string|null $extraClass
     * @return HtmlString
     */
    function btn_edit(string $url, string $title = 'Edit', ?string $extraClass = null): HtmlString
    {
        return ActionButtonHelper::edit($url, $title, $extraClass);
    }
}

if (!function_exists('btn_delete')) {
    /**
     * Render theme delete button.
     *
     * @param string $url
     * @param string|null $confirmMessage
     * @param string $title
     * @param string|null $extraClass
     * @param string|null $recordName
     * @return HtmlString
     */
    function btn_delete(string $url, ?string $confirmMessage = null, string $title = 'Delete', ?string $extraClass = null, ?string $recordName = null): HtmlString
    {
        return ActionButtonHelper::delete($url, $confirmMessage, $title, $extraClass, $recordName);
    }
}

if (!function_exists('btn_detail')) {
    /**
     * Render theme detail/view button.
     *
     * @param string $url
     * @param string $title
     * @param string|null $extraClass
     * @return HtmlString
     */
    function btn_detail(string $url, string $title = 'View details', ?string $extraClass = null): HtmlString
    {
        return ActionButtonHelper::detail($url, $title, $extraClass);
    }
}

if (!function_exists('btn_actions')) {
    /**
     * Render action buttons group.
     *
     * @param string|null $editUrl
     * @param string|null $deleteUrl
     * @param string|null $detailUrl
     * @param array $options
     * @return HtmlString
     */
    function btn_actions(?string $editUrl = null, ?string $deleteUrl = null, ?string $detailUrl = null, array $options = []): HtmlString
    {
        return ActionButtonHelper::actions($editUrl, $deleteUrl, $detailUrl, $options);
    }
}
