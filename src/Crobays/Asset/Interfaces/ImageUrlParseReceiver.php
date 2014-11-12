<?php namespace Crobays\Asset\Interfaces;

interface ImageUrlParseReceiver {

	public function setMultiplier($multiplier);
	public function setWidth($width);
	public function setHeight($height);
	public function setCropWidth($crop_width);
	public function setCropHeight($crop_height);
	public function setCropX($crop_x);
	public function setCropY($crop_y);
	public function setSourcePath($source_path);
	public function setDestPath($dest_path);

}