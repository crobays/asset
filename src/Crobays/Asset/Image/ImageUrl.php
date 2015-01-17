<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;

class ImageUrl {

	protected $keys = [
        'width' => 'w',
        'height' => 'h',
        'crop-width' => 'cw',
        'crop-height' => 'ch',
        'crop-x' => 'cx',
        'crop-y' => 'cy',
        'rotation' => 'r',
        'multiplier' => '@'
    ];

    protected $uri_args = array();

	protected $base_url;

	protected $url;

	protected $uri;

	protected $file;

	protected $file_base;

	protected $extension;

	protected $root_path;

	protected $uri_dir;

	protected $source_dir;

	protected $generator;

	protected $image_directories = array();

	protected $file_arguments_seperator;

	protected $argument_seperator;

	public function __construct()
	{
		$this->generator = new ImageGenerator;
	}

	public function setBaseUrl($base_url)
	{
		$this->base_url = $base_url;
		return $this;
	}

	public function setUrl($url)
	{
		$this->url = $url;

		# Look if the url has a domain name
		if (preg_match('/((?:http(?:s)?:?)?\/\/[^\/]*)(\S*)/', $url, $match))
		{
			if (count($match) < 3)
			{
				throw new Exception\InvalidImageUrlException("Invalid asset url pattern: $url", 1);
			}
			$this->setBaseUrl($match[1]);
			$this->setUri($match[2]);
		}
		else
		{
			$this->setUri($url);
		}

		return $this;
	}

	protected function parse()
	{
		$filename = basename($this->url);
	}

	protected function setUri($uri)
	{
		$this->uri = trim($uri, '/');
		$this->parseUri($this->uri);
		return $this;
	}

	protected function parseUri($uri)
	{
		foreach($this->image_directories as $k => $dirs)
		{
			$uri_dir = $dirs['uri']."/";
			if (substr($uri, 0, strlen($uri_dir)) === $uri_dir)
			{
				$this->setSourceDir($dirs['source']);
				$this->setUriDir($uri_dir);
				$file = substr($this->uri, strlen($uri_dir));
				$this->parseUriFile($file);
				return $this;
			}
		}
		throw new Exception("Error Processing Request", 1);
	}

	protected function parseUriFile($file)
	{
		$this->setFile($file);
		$sep = $this->file_arguments_seperator;
		$args = array();
		if($arguments_string = substr(strstr($this->file_base, $sep), strlen($sep)))
		{
			$this->parseArgumentString($arguments_string);
			$file_base = substr($this->file_base, 0, strlen($this->file_base) - strlen($sep.$arguments_string));
			$this->setFile($file_base.'.'.$this->extension);
		}
	}

	public function parseArgumentString($arguments_string)
	{
		$args = explode($this->argument_seperator, $arguments_string);
		foreach($args as $arg)
		{
			foreach(['([a-z]+)([0-9]+)', '(@)([0-9])x'] as $pattern)
			{
				if (preg_match("/$pattern/", $arg, $match))
				{
					break;
				}
			}

			if (count($match) < 3)
			{
				throw new Exception\InvalidImageArgumentException("Invalid image argument: $arg", 1);
			}
			
			$this->addArgument($match[1], $match[2]);
		}
	}

	public function addArgument($key, $value)
	{
		switch ($key)
		{
			case 'r':
				$this->setRotation($value);
				continue;
			case 'w':
				$this->setWidth($value);
				continue;
			case 'h':
				$this->setHeight($value);
				continue;
			case 'cw':
				$this->setCropWidth($value);
				continue;
			case 'ch':
				$this->setCropHeight($value);
				continue;
			case 'cx':
				$this->setCropX($value);
				continue;
			case 'cy':
				$this->setCropY($value);
				continue;
			case '@':
				$this->setMultiplier($value);
				continue;
			default:
				return $this;
        }

        return $this;
	}

	public function setImageDirectories($directories)
	{
		$this->image_directories = $directories;
		return $this;
	}

	public function setFileArgumentsSeperator($seperator)
	{
		$this->file_arguments_seperator = $seperator;
	}

	public function setArgumentSeperator($seperator)
	{
		$this->argument_seperator = $seperator;
	}

	public function uri()
	{
		if ($this->uri)
		{
			return $this->uri;
		}
		
		$args = array();
		foreach($this->keys as $item => $key)
		{
			if(is_null($value = $this->uriArg($item)))
			{
				continue;
			}
			if($key == '@')
			{
				$value .= 'x';
			}
			$args[] = str_replace($this->argument_seperator, '_', $key.$value);
		}

		if($args)
		{
			$file = $this->file_base
				.$this->file_arguments_seperator
				.implode($this->argument_seperator, $args)
				.'.'
				.$this->extension;

			$this->uri = $this->fetchPath($this->uri_dir, $file);
		}
		else
		{
			$this->uri = $this->fetchPath($this->uri_dir, $this->file_base.'.'.$this->extension);
		}
		
		return $this->uri;
	}

	public function url()
	{
		if ($this->url)
		{
			return $this->url;
		}

		$this->url = $this->fetchPath($this->base_url, $this->uri());
		if (\App::environment('local'))
		{
			$this->make();
		}
		
		return $this->url;
	}

	public function make()
	{
		$source_path = $this->fetchPath($this->root_path, $this->source_dir, $this->file);
		$dest_path = $this->fetchPath($this->root_path, $this->uri());
    	$this->generator->setSourcePath($source_path);
		$this->generator->setDestPath($dest_path);
		$this->generator->make();
		return $this;
	}

	public function setFile($file)
	{
		$this->file = $file;
		$file_base = strrev(strstr(strrev($file), '.'));
		$this->file_base = substr($file_base, 0, strlen($file_base)-1);
		$this->extension = strtolower(trim(strrchr($file, '.'), '.'));
        return $this;
	}

    public function setRootPath($root_path)
    {
        $this->root_path = $this->fetchPath($root_path);
        return $this;
    }

    public function setSourceDir($source_dir)
    {
        $this->source_dir = $this->fetchPath($source_dir);
        return $this;
    }

    public function setUriDir($uri_dir)
    {
        $this->uri_dir = $this->fetchPath($uri_dir);
        return $this;
    }

	public function setUriArg($item, $val)
    {
        if (strval(intval($val)) !== strval($val))
        {
        	return $this;
        }

    	# reset the uri and url because these will likely
        # be changed due to this method call
        $this->uri = NULL;
        $this->url = NULL;

        $this->uri_args[$item] = $val;
        return $this;
    }

    public function setRotation($degrees)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('rotation', $degrees);
    	$this->generator->setRotation($degrees);
        return $this;
    }

    public function setWidth($width)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('width', $width);
    	$this->generator->setWidth($width);
        return $this;
    }

    public function setHeight($height)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('height', $height);
    	$this->generator->setHeight($height);
        return $this;
    }

    public function setCropWidth($crop_width)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('crop-width', $crop_width);
    	$this->generator->setCropWidth($crop_width);
        return $this;
    }

    public function setCropHeight($crop_height)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('crop-height', $crop_height);
    	$this->generator->setCropHeight($crop_height);
        return $this;
    }

    public function setCropX($crop_x)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('crop-x', $crop_x);
    	$this->generator->setCropX($crop_x);
        return $this;
    }

    public function setCropY($crop_y)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$this->setUriArg('crop-y', $crop_y);
    	$this->generator->setCropY($crop_y);
        return $this;
    }

    public function setMultiplier($multiplier)
    {
    	if ( ! $this->manipulatable())
    	{
    		return $this;
    	}
    	$multiplier = intval(trim($multiplier, 'x'));
    	if ($multiplier < 2)
    	{
    		return $this;
    	}
    	$this->setUriArg('multiplier', $multiplier);
    	$this->generator->setMultiplier($multiplier);
        return $this;
    }

    public function manipulatable()
    {
    	return ! in_array($this->extension, ['svg']);
    }

    public function uriArg($item)
    {
        if (array_key_exists($item, $this->uri_args))
        {
        	return $this->uri_args[$item];
        }
        return NULL;
    }

    /**
     * Fetch path
     *
     * @var strings ...
     * @return string
     */
    protected function fetchPath()
    {
        $path = implode('/', func_get_args());
        return rtrim(preg_replace('/(\w)\/+/', '$1/', $path), '/');
    }
}
