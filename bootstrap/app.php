<?php

use App\Http\Middleware\AuthorizationMiddleware;
use App\Http\Middleware\HttpLogMiddleware;
use App\Http\Middleware\LangMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Configure unauthenticated redirects to admin.login
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // Global web middleware for query parameter ?lang= and audit logging
        $middleware->web(append: [
            LangMiddleware::class,
            HttpLogMiddleware::class,
        ]);

        $middleware->api(append: [
            LangMiddleware::class,
            HttpLogMiddleware::class,
        ]);

        $middleware->alias([
            'cms.auth' => AuthorizationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
