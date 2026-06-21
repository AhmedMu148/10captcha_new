<?php

use App\Http\Controllers\Api\CentralPaymentWebhookController;
use App\Http\Controllers\Api\OcrDataController;

use Illuminate\Support\Facades\Route;

// Central Payment Webhooks
Route::post('/webhooks/central-payment', [CentralPaymentWebhookController::class, 'handle'])
    ->name('webhooks.central-payment');

// OCR Daily Data Sync
Route::post('/data_uids.php', [OcrDataController::class, 'storeData'])
    ->name('ocr.data-uids');

Route::post('/secure/data-api.php', [OcrDataController::class, 'secureData'])
    ->name('ocr.secure-data');

// Block-Based CMS Api Routes
use App\Http\Controllers\Api\V1\CmsPageController;
use App\Http\Controllers\Api\V1\CmsSectionController;

Route::prefix('v1/cms')->name('api.cms.')->middleware(['cms.auth'])->group(function () {
    // Pages
    Route::middleware(['cms.scope:cms.pages.read'])->group(function () {
        Route::get('pages', [CmsPageController::class, 'index'])->name('pages.index');
        Route::get('pages/{page:slug}', [CmsPageController::class, 'show'])->name('pages.show');
    });

    Route::middleware(['cms.scope:cms.pages.write'])->group(function () {
        Route::post('pages', [CmsPageController::class, 'store'])->name('pages.store');
        Route::match(['put', 'patch'], 'pages/{page:slug}', [CmsPageController::class, 'update'])->name('pages.update');
    });

    // Sections
    Route::middleware(['cms.scope:cms.sections.read'])->group(function () {
        Route::get('sections', [CmsSectionController::class, 'index'])->name('sections.index');
        Route::get('sections/{section}', [CmsSectionController::class, 'show'])->name('sections.show');
    });

    Route::middleware(['cms.scope:cms.sections.write'])->group(function () {
        Route::post('sections', [CmsSectionController::class, 'store'])->name('sections.store');
        Route::match(['put', 'patch'], 'sections/{section}', [CmsSectionController::class, 'update'])->name('sections.update');
    });
});

