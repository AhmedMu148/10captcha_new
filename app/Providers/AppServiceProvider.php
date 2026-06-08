<?php

namespace App\Providers;

use App\Services\CentralPaymentIntegrationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CentralPaymentIntegrationService::class, function () {
            return new CentralPaymentIntegrationService(
                config('services.central_payment.base_url'),
                config('services.central_payment.api_key'),
                config('services.central_payment.secret_key'),
                config('services.central_payment.api_version', 'v1'),
                (int) config('services.central_payment.timeout', 30),
                filter_var(config('services.central_payment.verify_ssl', true), FILTER_VALIDATE_BOOLEAN)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
