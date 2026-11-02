<?php

namespace App\Helpers;

use App\Models\UploadSetup;
use Illuminate\Support\Facades\Schema;

class UploadSetupHelper
{
    /**
     * Get MUploadSetup model or array for a given module identifier and field name.
     *
     * @param string|null $moduleId Module UUID, name, or code (e.g., 'user', 'users')
     * @param string $name Field name (e.g., 'image_src')
     * @return UploadSetup|null
     */
    public static function getSetup(?string $moduleId, string $name): ?UploadSetup
    {
        if (!Schema::hasTable('m_upload_setup')) {
            return null;
        }

        return UploadSetup::where(function ($query) use ($moduleId) {
                $query->where('m_module_id', $moduleId);
                if ($moduleId === 'users') {
                    $query->orWhere('m_module_id', 'user');
                } elseif ($moduleId === 'user') {
                    $query->orWhere('m_module_id', 'users');
                }
            })
            ->where('name', $name)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get HTML data attributes string for an upload input field.
     *
     * @param string|null $moduleId
     * @param string $name
     * @param array $extra
     * @return string
     */
    public static function getAttributes(?string $moduleId, string $name, array $extra = []): string
    {
        $setup = self::getSetup($moduleId, $name);

        $previewSelector = $extra['preview'] ?? '#preview_' . $name;
        $setValueSelector = $extra['setvalue'] ?? '#' . $name;

        $path = $setup ? $setup->path : 'assets/images/' . ($moduleId ?: 'user');
        $isExternal = $setup ? (int) $setup->is_external_storage : 0;
        $allowedFiles = $setup && !empty($setup->allowed_files) ? $setup->allowed_files : 'png,jpg,jpeg,gif,webp';
        $isResized = $setup ? (int) $setup->is_resized : 0;
        $picHeight = $setup ? (int) $setup->pic_height : 0;
        $picWidth = $setup ? (int) $setup->pic_width : 0;
        $isCropped = $setup ? (int) $setup->is_cropped : 0;
        $ratioHeight = $setup ? (int) ($setup->ratio_height ?: 1) : 1;
        $ratioWidth = $setup ? (int) ($setup->ratio_width ?: 1) : 1;
        $maxSize = $setup ? (int) ($setup->max_size ?: 2048) : 2048;
        $type = $setup ? ($setup->type ?: 'image') : 'image';

        $attrs = [
            'data-module'           => $moduleId ?: 'user',
            'data-name'             => $name,
            'data-preview'          => $previewSelector,
            'data-setvalue'         => $setValueSelector,
            'data-path'             => $path,
            'data-is-external'      => $isExternal,
            'data-allowed-files'    => $allowedFiles,
            'data-is-resized'       => $isResized,
            'data-pic-height'       => $picHeight,
            'data-pic-width'        => $picWidth,
            'data-is-cropped'       => $isCropped,
            'data-ratio-height'     => $ratioHeight,
            'data-ratio-width'      => $ratioWidth,
            'data-max-size'         => $maxSize,
            'data-type'             => $type,
        ];

        // Merge extra attributes
        foreach ($extra as $key => $val) {
            if (!in_array($key, ['preview', 'setvalue'], true)) {
                $attrs['data-' . $key] = $val;
            }
        }

        $html = [];
        foreach ($attrs as $k => $v) {
            $html[] = sprintf('%s="%s"', $k, htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'));
        }

        return implode(' ', $html);
    }
}
