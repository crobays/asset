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
		$app = $this->app;
		$app['asset'] = $app->share(function ($app) {
            $asset_manager = new AssetManager(\App::make('config'));
            return $asset_manager;
        });

        $this->app['command.asset.generate-css-images'] = $this->app->share(function($app)
        {
            return new Commands\CssImagesGeneratorCommand;
        });
        $this->commands('command.asset.generate-css-images');
	}

	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('crobays/asset');
		AliasLoader::getInstance()->alias('Asset', 'Crobays\Asset\Facades\Asset');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('asset');
	}

}
