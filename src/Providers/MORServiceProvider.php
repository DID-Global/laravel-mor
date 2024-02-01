<?php

namespace MOR\Providers;

use Illuminate\Support\ServiceProvider;

class MORServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mor', function ($app) {
            return new \MOR\MOR();
        });

        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('MOR', 'MOR\Facades\MOR');
        });

        $configPath = config_path('mor.php');

        if (!\Illuminate\Support\Facades\File::exists($configPath)) {
            $this->publishes([
                __DIR__ . '/../config/mor.php' => $configPath
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('mor');
    }

}
