<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        // Script ini memaksa Laravel menggunakan HTTPS saat di Ngrok
        if (str_contains(config('app.url'), 'ngrok') || str_contains(config('app.url'), 'loca.lt')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
