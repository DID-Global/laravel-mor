<?php

namespace MOR\Providers;

use Illuminate\Support\ServiceProvider;

class MORServiceProvider extends ServiceProvider {

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
		$this->app->bind('mor', function($app) {
		    return new MOR;
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('MOR', 'MOR\Facades\MOR');
		});

		$this->publishes([
			dirname(__FILE__).'/config/mor.php' => config_path('mor.php')
		]);
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
