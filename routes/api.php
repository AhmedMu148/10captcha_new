<?php

use App\Http\Controllers\Api\CentralPaymentWebhookController;
use Illuminate\Support\Facades\Route;

// Central Payment Webhooks
Route::post('/webhooks/central-payment', [CentralPaymentWebhookController::class, 'handle'])
    ->name('webhooks.central-payment');