<?php

namespace App\Providers;

use App\Helpers\CMSAccessHelper;
use App\Services\ModuleAccessService;
use App\Services\ModuleService;
use Illuminate\Support\ServiceProvider;

class ModuleAccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('cms.access', function ($app) {
            return new CMSAccessHelper(
                $app->make(ModuleAccessService::class),
                $app->make(ModuleService::class)
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
