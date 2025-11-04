<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ZakatPayment;
use App\Models\Muzakki;
use App\Observers\ZakatPaymentObserver;
use App\Observers\MuzakkiObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ZakatPayment::observe(ZakatPaymentObserver::class);
        Muzakki::observe(MuzakkiObserver::class);

        // Force HTTPS for assets when request is HTTPS (for ngrok/tunnel services)
        if (request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
