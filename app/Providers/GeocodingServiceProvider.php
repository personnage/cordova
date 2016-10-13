<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\GeocodingRepositories;
use App\Repositories\GoogleGeocodingRepositories;

class GeocodingServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeocodingRepositories::class, GoogleGeocodingRepositories::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [GeocodingRepositories::class];
    }
}
