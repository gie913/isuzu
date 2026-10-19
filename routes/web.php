<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Fallback named route for standard Laravel auth redirection
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes (Guest)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'moduleName'  => 'Dashboard',
                'breadcrumbs' => [
                    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ],
            ]);
        })->name('dashboard');

        // Users Module
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/list', [UserController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [UserController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [UserController::class, 'add'])->name('add');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

                // Role Module
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\RoleController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\RoleController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\RoleController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\RoleController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\RoleController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\RoleController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\RoleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\RoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\RoleController::class, 'destroy'])->name('destroy');
        });
        /*==new route==*/
    });
     
});
