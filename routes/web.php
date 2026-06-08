<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketSupportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ApiPageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomImageController;
use App\Http\Controllers\WalletController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/api-docs', [ApiPageController::class, 'docs'])->name('api.docs');
Route::get('/tos', fn() => view('tos'))->name('tos');
Route::get('/privacy-policy', fn() => view('privacy'))->name('privacy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api', [ApiPageController::class, 'index'])->name('api.page');
    Route::post('/api/regenerate', [ApiPageController::class, 'regenerate'])->name('api.regenerate');

    Route::get('/topup', [PaymentController::class, 'topup'])->name('topup');
    Route::get('/payments', [PaymentController::class, 'history'])->name('payments.history');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    // Wallet routes
    Route::post('/wallet/process-topup', [WalletController::class, 'processTopUp'])->name('wallet.process-topup');
    Route::get('/wallet/success', [WalletController::class, 'topUpSuccess'])->name('wallet.success');

    Route::get('/custom-images', [CustomImageController::class, 'index'])->name('custom-image.index');
    Route::get('/custom-images/test', [CustomImageController::class, 'testForm'])->name('custom-image.test');
    Route::post('/custom-images/test', [CustomImageController::class, 'storeTest'])->name('custom-image.store');
    Route::get('/custom-images/results/{hash}', [CustomImageController::class, 'showResults'])->name('custom-image.results');
    Route::post('/custom-images/send-records', [CustomImageController::class, 'sendRecords'])->name('custom-image.send-records');
    Route::get('/custom-images/test-ocr', [CustomImageController::class, 'testOcr'])->name('custom-image.test-ocr');


    Route::get('/tickets/sso', [TicketSupportController::class, 'sso'])
        ->name('ticket.sso.redirect');

    // Ticket SSO: handle intended URL from ticket system.
    Route::get('/tickets/sso/intended', [TicketSupportController::class, 'handleIntended'])
        ->name('ticket.sso.intended');

});

require __DIR__.'/auth.php';
