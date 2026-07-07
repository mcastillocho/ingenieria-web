<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Forzar HTTPS en todas las URLs generadas por Laravel (route(), url(), asset())
        // cuando la app corre en producción detrás de un reverse proxy (Nginx/Traefik).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
