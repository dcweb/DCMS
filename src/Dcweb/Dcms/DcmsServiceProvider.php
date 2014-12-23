<?php namespace Dcweb\Dcms;

use Illuminate\Support\ServiceProvider;
use Config;

class DcmsServiceProvider extends ServiceProvider {

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
		$this->package('dcweb/dcms');
		include __DIR__.'/../../routes.php';
		include __DIR__.'/../../filters.php';

		// Add package database configurations to the default set of configurations
    $this->app['config']['database.connections'] = array_merge(
        $this->app['config']['database.connections']
       ,\Config::get('dcms::database.connections')
    );
		
		// Use package auth
//		$this->app['config']['auth'] = \Config::get('dcms::auth');
		//array_replace_recursive
		$this->app['config']['auth'] = array_replace_recursive($this->app["config"]["auth"],\Config::get('dcms::auth'));
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
