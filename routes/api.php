<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

    // Protected API routes using Laravel Passport
    Route::middleware('auth:api')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('profile', [AuthController::class, 'profile']);
            Route::post('logout', [AuthController::class, 'logout']);
        });

        // Users Resource API
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::post('/', [UserController::class, 'store']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

                // Role Resource API
        Route::prefix('roles')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\RoleController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\RoleController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\RoleController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\RoleController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\RoleController::class, 'destroy']);
        });
                // Module Resource API
        Route::prefix('modules')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ModuleController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ModuleController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\ModuleController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ModuleController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ModuleController::class, 'destroy']);
        });
        /*==new route==*/

    });
});
