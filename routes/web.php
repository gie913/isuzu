<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Landing Page (Index)
Route::get('/', function () {
    return view('frontend.index');
})->name('index');

// Backward compatibility for /mockup
Route::get('/mockup', function () {
    return redirect()->route('index');
});

// Frontend Produk Page
Route::get('/produk', function () {
    return view('frontend.produk');
})->name('produk');

// Frontend Detail Produk Page
Route::get('/detail-produk', function () {
    return view('frontend.detail_produk');
})->name('detail-produk');

// Frontend Layanan Page
Route::get('/layanan', function () {
    return view('frontend.layanan');
})->name('layanan');

// Frontend Sparepart Page
Route::get('/sparepart', function () {
    return view('frontend.sparepart');
})->name('sparepart');

// Frontend Berita / News Page
Route::get('/news', function () {
    return view('frontend.news');
})->name('news');

// Frontend Brosur Page
Route::get('/brosur', function () {
    return view('frontend.brosur');
})->name('brosur');

// Frontend Kontak Page
Route::get('/kontak', function () {
    return view('frontend.kontak');
})->name('kontak');

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
                // Module Module
        Route::prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\ModuleController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\ModuleController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\ModuleController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\ModuleController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\ModuleController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\ModuleController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\ModuleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\ModuleController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\ModuleController::class, 'destroy'])->name('destroy');
        });
        /*==new route==*/
    });
     
});
