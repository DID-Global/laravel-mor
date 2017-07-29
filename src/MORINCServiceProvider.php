<?php

namespace MORINC;

use Illuminate\Support\ServiceProvider;

class MORINCServiceProvider extends ServiceProvider {

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
		$this->app->bind('morinc', function($app) {
		    return new MORINC;
		});

		$this->app->booting(function() {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('MORINC', 'MORINC\Facades\MORINC');
		});

		$this->publishes([
			dirname(__FILE__).'/config/morinc.php' => config_path('morinc.php')
		]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('morinc');
	}

}
