<?php namespace Crobays\Asset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
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
		// $this->app->bind('asset', function($app){
		// 	return new Asset();
		// });
		$app = $this->app;
		$app['asset'] = $app->share(function ($app) {
//			$config = $app['config']->get('asset::config');
            $asset_manager = new AssetManager(\App::make('config'));
//            $asset_manager->configure($config);
            return $asset_manager;
        });
        // CLI
        $this->app->bindShared('command.asset.generate-css-images', function()
        {
            return new Commands\CssImagesGeneratorCommand;
        });
		//$this->app->bind('asset', 'Crobays\Asset\Asset');
	}

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
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
