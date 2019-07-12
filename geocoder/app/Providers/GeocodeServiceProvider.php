<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\GeoCode;

class GeocodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\GeoCode', function ($app) {
            return new GeoCode();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
