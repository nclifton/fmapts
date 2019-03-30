<?php

namespace App\Providers;

use App\Api\Client;
use Illuminate\Support\ServiceProvider;
use Torann\RemoteModel\Model;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setClient($this->app['apiclient']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('apiclient', function () {
            return new Client(); // API Client
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'apiclient'
        ];
    }
}
