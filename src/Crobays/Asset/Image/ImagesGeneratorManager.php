<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;
use Crobays\Asset\Interfaces\ImageFinderInterface;
use Crobays\Asset\Interfaces\LoggerInterface;
use Config;

class ImagesGeneratorManager {

	public $finder;

	public $log;

	protected $root_path;

	protected $images = array();

	public function __construct(ImageFinderInterface $finder, LoggerInterface $logger)
	{
		$this->finder = $finder;
		$this->log = $logger;
		$this->config = Config::get('asset::config');
		$this->root_path = $this->config['root-path'];
	}

	public function setRootPath($root_path)
	{
		$this->root_path = $root_path;
	}

	public function generateImagesFromFile($file)
	{
		if ( ! $this->finder->setFile($file))
		{
			$this->log->error("Invalid file $file");
			return $this;
		}
		
		foreach($this->finder->searchImages() as $url_string)
		{
			$image_url = new ImageUrl;
			$image_url->setBaseUrl($this->config['url']);
	        $image_url->setRootPath($this->root_path);
	       	$image_url->setImageDirectories($this->config['images-directories']);
	       	$image_url->setFileArgumentsSeperator($this->config['file-arguments-seperator']);
	       	$image_url->setArgumentSeperator($this->config['argument-seperator']);
			$image_url->setUrl($url_string);
			try
			{
				$image_url->make();
			}
			catch(\RuntimeException $e)
			{
			   $this->log->error($e->getMessage());
			   continue;
			}
			$this->log->line("Created: $url_string");
		}

		$this->log->info('Done!');
		return $this;
	}

}