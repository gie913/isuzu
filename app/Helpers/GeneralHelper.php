<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class GeneralHelper
{
    /**
     * Generate standard reference code.
     *
     * @param string $prefix
     * @return string
     */
    public static function generateReference(string $prefix = 'REF'): string
    {
        return sprintf('%s-%s-%s', strtoupper($prefix), date('Ymd'), strtoupper(Str::random(6)));
    }

    /**
     * Format date to standard app format.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    public static function formatDate(mixed $date, string $format = 'Y-m-d H:i:s'): string
    {
        if (empty($date)) {
            return '-';
        }

        return Carbon::parse($date)->format($format);
    }
}
