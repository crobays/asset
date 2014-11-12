<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;

class CssImageFinder extends FileImageFinder {

	protected $file;

	protected $images = array();

	public function searchImages()
	{
		$dirs = implode('|', ['pic', 'img']);
		preg_match_all("/url\([\"']?(\/(?:$dirs)\/[^\"']*)[\"']?\)/", $this->getFileContents(), $matches);			
		$this->images = $matches[1];
		return $this->images;
	}
	
}