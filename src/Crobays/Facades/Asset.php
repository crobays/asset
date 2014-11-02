<?php namespace Crobays\Facades;
//echo 'loaded Facades/Asset';
use Illuminate\Support\Facades\Facade;

class Asset extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'asset';
	}
}