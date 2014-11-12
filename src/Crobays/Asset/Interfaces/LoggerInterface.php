<?php namespace Crobays\Asset\Interfaces;

interface LoggerInterface {

	public function info($string);
	public function line($string);
	public function comment($string);
	public function error($string);
	
}