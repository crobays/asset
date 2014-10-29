<?php namespace Crobays\Asset;

use Illuminate\Support\Facades\Config;

/**
* 
*/
class Asset
{
	protected static $instance;

	public $config;
	public $url;
	public $pic;
	public $img;
	public $css;
	public $js;

	public function __construct()
	{
		$this->url = Config::get('asset::url');
		$this->pic = Config::get('asset::pic');
		$this->img = Config::get('asset::img');
		$this->css = Config::get('asset::css');
		$this->js = Config::get('asset::js');
	}

	 /**
     * Handle dynamic method calls
     *
     * @param string $name
     * @param array $args
     */
    public static function __callStatic($name, $args)
    {
        $instance = static::$instance;
        if ( ! $instance) $instance = static::$instance = new static;

        $base_url = str_replace('$URL', $instance->url, $instance->{$name});
        $parts = $args;
        array_unshift($parts, $base_url);
        return implode('/', $parts);
    }

}