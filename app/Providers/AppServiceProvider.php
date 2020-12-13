<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        //  URL::forceScheme('https');
        // if(\App::environment('production')) {
        //     $url->forceScheme('https');
        //     $this->app['request']->server->set('HTTPS', true);
        // }
    }
}
