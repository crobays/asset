<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;

abstract class FileImageFinder implements \Crobays\Asset\Interfaces\ImageFinderInterface {

	protected $file;

	public function setFile($file)
	{
		if ( ! is_file($file))
		{
			return FALSE;
		}
		$this->file = $file;
		return TRUE;
	}

	public function getFileContents()
	{
		return file_get_contents($this->file);
	}

}
