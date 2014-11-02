<?php namespace Crobays\Asset;

use Illuminate\Support\Facades\Config;

/**
* 
*/
class Asset
{
    public function url()
    {
        $path = func_get_args();
        array_unshift($path, Config::get('asset::url'));
        $path = array_map('trim', $path);
        return join('/', $path);
    }

    public function img($file)
    {
        return static::url(Config::get('asset::img-dir'), $file);
    }

    public function pic($file)
    {
        return static::url(Config::get('asset::pic-dir'), $file);
    }

    public function file($file)
    {
        return static::url(Config::get('asset::file-dir'), $file);
    }

    public function css($file = NULL)
    {
        if (is_null($file))
        {
            $file = Config::get('asset::css');
        }
        return static::url(Config::get('asset::css-dir'), $file);
    }

    public function js($file = NULL)
    {
        if (is_null($file))
        {
            $file = Config::get('asset::js');
        }
        return static::url(Config::get('asset::js-dir'), $file);
    }

}