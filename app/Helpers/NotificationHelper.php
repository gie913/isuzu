<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class NotificationHelper
{
    /**
     * Flash success notification to session.
     *
     * @param string $message
     */
    public static function success(string $message): void
    {
        Session::flash('flash_notification', [
            'type'    => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Flash error notification to session.
     *
     * @param string $message
     */
    public static function error(string $message): void
    {
        Session::flash('flash_notification', [
            'type'    => 'danger',
            'message' => $message,
        ]);
    }
}
