<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema; // Asegúrate de tener este 'use'
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
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // AÑADE ESTA LÍNEA
        \Carbon\Carbon::setLocale(config('app.locale'));
    }
}
