<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class TranslationHelper
{
    /**
     * Get translation or default fallback.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public static function get(string $key, array $replace = [], ?string $locale = null): string
    {
        return trans($key, $replace, $locale ?? App::getLocale());
    }
}
