<?php namespace Crobays\Asset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AssetServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	//protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['asset'] = $this->app->share(function($app){
			return new Asset;
		});
		//$this->app->bind('asset', 'Crobays\Asset\Asset');
	}

	public function boot()
	{
		$this->package('crobays/asset');
		AliasLoader::getInstance()->alias('Asset', 'Crobays\Facades\Asset');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	// public function provides()
	// {
	// 	return array();
	// }

}
