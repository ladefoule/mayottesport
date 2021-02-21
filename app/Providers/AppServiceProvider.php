<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
         Schema::defaultStringLength(191);
        //  Paginator::useBootstrap(); // CSS de pagination via Bootstrap au lieu de Tailwind
        //  URL::forceScheme('https');
        // if(\App::environment('production')) {
        //     $url->forceScheme('https');
        //     $this->app['request']->server->set('HTTPS', true);
        // }
    }
}
