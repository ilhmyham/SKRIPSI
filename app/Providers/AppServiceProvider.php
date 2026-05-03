<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS if not on localhost
        if (request()->server('HTTP_HOST') !== 'localhost' && request()->server('HTTP_HOST') !== '127.0.0.1:8000') {
            URL::forceScheme('https');
        }
    }
}
