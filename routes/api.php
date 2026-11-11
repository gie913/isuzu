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

    // Upload Endpoints (File and Cropped Upload)
    Route::post('upload-file', [\App\Http\Controllers\Api\UploadController::class, 'uploadFile']);
    Route::post('upload-cropped-image', [\App\Http\Controllers\Api\UploadController::class, 'uploadCroppedImage']);
    Route::get('upload-setup/config', [\App\Http\Controllers\Api\UploadSetupController::class, 'getConfiguration']);

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
                // GeneralData Resource API
        Route::prefix('general_datas')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\GeneralDataController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\GeneralDataController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\GeneralDataController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\GeneralDataController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\GeneralDataController::class, 'destroy']);
        });
                // Upload Resource API
        Route::prefix('uploads')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\UploadController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\UploadController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\UploadController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\UploadController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\UploadController::class, 'destroy']);
        });
                // UploadSetup Resource API
        Route::prefix('upload_setups')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\UploadSetupController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\UploadSetupController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\UploadSetupController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\UploadSetupController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\UploadSetupController::class, 'destroy']);
        });
                // MProduct Resource API
        Route::prefix('m_products')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\MProductController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\MProductController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\MProductController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\MProductController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\MProductController::class, 'destroy']);
        });
                // TProductApplication Resource API
        Route::prefix('t_product_applications')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\TProductApplicationController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\TProductApplicationController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\TProductApplicationController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\TProductApplicationController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\TProductApplicationController::class, 'destroy']);
        });
                // Product Resource API
        Route::prefix('products')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ProductController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\ProductController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ProductController::class, 'destroy']);
        });
                // BaseSetup Resource API
        Route::prefix('base_setups')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\BaseSetupController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\BaseSetupController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\BaseSetupController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\BaseSetupController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\BaseSetupController::class, 'destroy']);
        });
                // Banner Resource API
        Route::prefix('banners')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\BannerController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\BannerController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\BannerController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\BannerController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\BannerController::class, 'destroy']);
        });
                // Content Resource API
        Route::prefix('contents')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ContentController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ContentController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\ContentController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ContentController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ContentController::class, 'destroy']);
        });
                // ContentDtl Resource API
        Route::prefix('content_dtls')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ContentDtlController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ContentDtlController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\ContentDtlController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ContentDtlController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ContentDtlController::class, 'destroy']);
        });
                // Page Resource API
        Route::prefix('pages')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\PageController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\PageController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\PageController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\PageController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\PageController::class, 'destroy']);
        });
                // Attachment Resource API
        Route::prefix('attachments')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\AttachmentController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\AttachmentController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\AttachmentController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\AttachmentController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\AttachmentController::class, 'destroy']);
        });
                // Menu Resource API
        Route::prefix('menus')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\MenuController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\MenuController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\MenuController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\MenuController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\MenuController::class, 'destroy']);
        });
                // Message Resource API
        Route::prefix('messages')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\MessageController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\MessageController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\MessageController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\MessageController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\MessageController::class, 'destroy']);
        });
                // Client Resource API
        Route::prefix('clients')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\ClientController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\ClientController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\ClientController::class, 'destroy']);
        });
                // Testimonial Resource API
        Route::prefix('testimonials')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\TestimonialController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Api\TestimonialController::class, 'show']);
            Route::post('/', [\App\Http\Controllers\Api\TestimonialController::class, 'store']);
            Route::put('/{id}', [\App\Http\Controllers\Api\TestimonialController::class, 'update']);
            Route::delete('/{id}', [\App\Http\Controllers\Api\TestimonialController::class, 'destroy']);
        });
        /*==new route==*/

    });
});
