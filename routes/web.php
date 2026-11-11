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

        // My Profile & Change Password
        Route::get('/my-profile', [UserController::class, 'myProfile'])->name('my-profile');
        Route::put('/my-profile', [UserController::class, 'updateMyProfile'])->name('my-profile.update');
        Route::get('/change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::put('/change-password', [UserController::class, 'updatePassword'])->name('change-password.update');

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
                // GeneralData Module
        Route::prefix('general_datas')->name('general_datas.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\GeneralDataController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\GeneralDataController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\GeneralDataController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\GeneralDataController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\GeneralDataController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\GeneralDataController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\GeneralDataController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\GeneralDataController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\GeneralDataController::class, 'destroy'])->name('destroy');
        });
                // Upload Module
        Route::prefix('uploads')->name('uploads.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\UploadController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\UploadController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\UploadController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\UploadController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\UploadController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\UploadController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\UploadController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\UploadController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\UploadController::class, 'destroy'])->name('destroy');
        });
                // UploadSetup Module
        Route::prefix('upload_setups')->name('upload_setups.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\UploadSetupController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\UploadSetupController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\UploadSetupController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\UploadSetupController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\UploadSetupController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\UploadSetupController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\UploadSetupController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\UploadSetupController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\UploadSetupController::class, 'destroy'])->name('destroy');
        });
                // MProduct Module
        Route::prefix('m_products')->name('m_products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\MProductController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\MProductController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\MProductController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\MProductController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\MProductController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\MProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\MProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\MProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\MProductController::class, 'destroy'])->name('destroy');
        });
                // TProductApplication Module
        Route::prefix('t_product_applications')->name('t_product_applications.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\TProductApplicationController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\TProductApplicationController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\TProductApplicationController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\TProductApplicationController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\TProductApplicationController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\TProductApplicationController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\TProductApplicationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\TProductApplicationController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\TProductApplicationController::class, 'destroy'])->name('destroy');
        });
                // Product Module
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\ProductController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\ProductController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\ProductController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\ProductController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\ProductController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\ProductController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\ProductController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\ProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\ProductController::class, 'destroy'])->name('destroy');
        });
                // BaseSetup Module
        Route::prefix('base-setup')->name('base_setups.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\BaseSetupController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\BaseSetupController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\BaseSetupController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\BaseSetupController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\BaseSetupController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\BaseSetupController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\BaseSetupController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\BaseSetupController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\BaseSetupController::class, 'destroy'])->name('destroy');
        });
                // Banner Module
        Route::prefix('banners')->name('banners.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\BannerController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\BannerController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\BannerController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\BannerController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\BannerController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\BannerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\BannerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\BannerController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\BannerController::class, 'destroy'])->name('destroy');
        });
                // Content Module
        Route::prefix('contents')->name('contents.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\ContentController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\ContentController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\ContentController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\ContentController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\ContentController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\ContentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\ContentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\ContentController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\ContentController::class, 'destroy'])->name('destroy');
        });
                // ContentDtl Module
        Route::prefix('content_dtls')->name('content_dtls.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\ContentDtlController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\ContentDtlController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\ContentDtlController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\ContentDtlController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\ContentDtlController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\ContentDtlController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\ContentDtlController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\ContentDtlController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\ContentDtlController::class, 'destroy'])->name('destroy');
        });
                // Page Module
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\PageController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\PageController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\PageController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\PageController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\PageController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\PageController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\PageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\PageController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\PageController::class, 'destroy'])->name('destroy');
        });
                // Attachment Module
        Route::prefix('attachments')->name('attachments.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\AttachmentController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\AttachmentController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\AttachmentController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\AttachmentController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\AttachmentController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\AttachmentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\AttachmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\AttachmentController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\AttachmentController::class, 'destroy'])->name('destroy');
        });
                // Menu Module
        Route::prefix('menus')->name('menus.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\MenuController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\MenuController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\MenuController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\MenuController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\MenuController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\MenuController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\MenuController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\MenuController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\MenuController::class, 'destroy'])->name('destroy');
        });
                // Message Module
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\MessageController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\MessageController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\MessageController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\MessageController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\MessageController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\MessageController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\MessageController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\MessageController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\MessageController::class, 'destroy'])->name('destroy');
        });
                // Client Module
        Route::prefix('clients')->name('clients.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\ClientController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\ClientController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\ClientController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\ClientController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\ClientController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\ClientController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\ClientController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\ClientController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\ClientController::class, 'destroy'])->name('destroy');
        });
                // Testimonial Module
        Route::prefix('testimonials')->name('testimonials.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Web\TestimonialController::class, 'index'])->name('index');
            Route::get('/list', [\App\Http\Controllers\Web\TestimonialController::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\App\Http\Controllers\Web\TestimonialController::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\App\Http\Controllers\Web\TestimonialController::class, 'add'])->name('add');
            Route::post('/', [\App\Http\Controllers\Web\TestimonialController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Web\TestimonialController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Web\TestimonialController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Web\TestimonialController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Web\TestimonialController::class, 'destroy'])->name('destroy');
        });
        /*==new route==*/
    });
     
});
