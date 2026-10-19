<?php

namespace App\Providers;

use App\Helpers\CMSAccessHelper;
use App\Services\CMSAccessService;
use App\Services\CMSMenuService;
use Illuminate\Support\ServiceProvider;

class CMSAccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('cms.access', function ($app) {
            return new CMSAccessHelper(
                $app->make(CMSAccessService::class),
                $app->make(CMSMenuService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
