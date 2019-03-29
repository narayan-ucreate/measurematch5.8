<?php

namespace App\Providers;

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
    public function boot()
    {
         if (env('ENVIRONMENT') != 'local') {
             \URL::forceScheme('https');
         }
        view()->share('random_number', getenv('CACHING_COUNTER'));
        view()->share('ssl', getenv('APP_SSL'));
    }
}
