<?php namespace Crobays\Asset\Image;

use Intervention\Image\ImageManager as Manipulator;
use Crobays\Asset\Exception;

class Image extends \Crobays\Asset\Asset {

    public $image_directory_key = 'image';

    public $manipulation = array();

    public $manipulation_keys = [
        'width' => 'w',
        'height' => 'h',
        'crop-width' => 'cw',
        'crop-height' => 'ch',
        'crop-x' => 'cx',
        'crop-y' => 'cy',
        'multiplier' => '@',
    ];

    public $attributes = [
        'width' => NULL,
        'height' => NULL,
        'src' => FALSE,
    ];

    protected $source_path;

    protected $has_manipulations = FALSE;

    protected $image;

    protected $manipulator;

    protected $multiplier = 1;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->manipulator = new Manipulator;
    }

    /**
     * Get the assets url
     *
     * @return string
     */
    public function hasManipulation()
    {
        switch ($this->getExtension())
        {
            case 'svg';
                return FALSE;
                break;
        }

        return $this->manipulation;
    }

    /**
     * Get the image directory
     *
     * @return string
     */
    public function getUri()
    {
        $file_name = $this->getFileNameBase();
        if ($this->hasManipulation())
        {
            $arguments_sep = $this->getConfigItem('arguments-seperator');
            $file_name .= $arguments_sep.$this->getUriArgumentsString();
        }
        $dir = dirname($this->uri);
        return $this->fetchPath($dir, $file_name.'.'.$this->getExtension());
    }

    /**
     * Get the image directory
     *
     * @return string
     */
    public function getUriArgumentsString()
    {
        $argument_sep = $this->getConfigItem('argument-seperator');
        $args = array();
        foreach($this->manipulation_keys as $long => $short)
        {
            if(is_null($val = $this->getManipulationValue($long)))
            {
                continue;
            }
            $args[] = $short.$val;
        }
        
        return implode($argument_sep, $args);
    }

    public function make()
    {
        if( ! $this->outdated())
        {
            return $this;
        }

        @mkdir(dirname($this->getNewPath()), 0777, TRUE);
        if ( ! $this->hasManipulation())
        {
            copy($this->getSourcePath(), $this->getNewPath());
            return $this;
        }
        
        $this->image = $this->manipulator->make($this->getSourcePath());

        if($this->getResizeWidth() && $this->getResizeHeight())
        {
            $this->image->fit($this->getResizeWidth(), $this->getResizeHeight(), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        else if($this->getResizeWidth() || $this->getResizeHeight())
        {
            $this->image->resize($this->getResizeWidth(), $this->getResizeHeight(), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if($this->getCropWidth() || $this->getCropHeight())
        {
            $cw = $this->getCropWidth() ?: $this->getResizeWidth() ?: $this->image->width();
            $ch = $this->getCropHeight() ?: $this->getResizeHeight() ?: $this->image->height();
            $this->image->crop($cw, $ch, $this->getCropX(), $this->getCropY());
        }
        
        $this->image->save($this->getNewPath());

        return $this;
    }

    public function response()
    {
        return $this->image->response($this->getExtension());
    }

    public function getRootPath()
    {
        $root_path = $this->getConfigItem('root-path');
        $path = $this->fetchPath($root_path);
        if(substr($root_path, 0, 1) == '/')
        {
            $path = "/$path";
        }
        return $path;
    }

    public function setUri($uri)
    {
        $dirs = $this->getConfigItem($key_1 = 'images-directories');
        if ( ! array_key_exists($key_2 = $this->image_directory_key, $dirs))
        {
            throw new Exception\MissingConfigItemKeyException("Missing config item: $key_1 -> $key_2", 1);
        }

        if ( ! array_key_exists($key_3 = 'source', $dirs[$this->image_directory_key]))
        {
            throw new Exception\MissingConfigItemKeyException("Missing config item: $key_1 -> $key_2 -> $key_3", 1);
        }

        if ( ! array_key_exists($key_3 = 'uri', $dirs[$this->image_directory_key]))
        {
            throw new Exception\MissingConfigItemKeyException("Missing config item: $key_1 -> $key_2 -> $key_3", 1);
        }

        $source_dir = $this->fetchPath($dirs[$this->image_directory_key]['source']);
        $uri_base = $this->fetchPath($dirs[$this->image_directory_key]['uri']);
        
        $this->generateSourcePath($this->fetchPath($source_dir, $uri));
        
        $this->uri = $this->fetchPath($uri_base, $uri);
        return $this;        
    }

    /**
     * Set image manipulation parameters
     *
     * @return string
     */
    public function setParams(array $params)
    {
        foreach($params as $k => $v)
        {
            switch ($k) {
                case 's':
                case 'size':
                    $this->setSize($v);
                    continue 2;
                case 'w':
                case 'width':
                    $this->setResizeWidth($v);
                    continue 2;
                case 'h':
                case 'height':
                    $this->setResizeHeight($v);
                    continue 2;
                case 'cw':
                case 'crop-width':
                    $this->setCropWidth($v);
                    continue 2;
                case 'ch':
                case 'crop-height':
                    $this->setCropHeight($v);
                    continue 2;
                case 'cx':
                case 'crop-x':
                    $this->setCropX($v);
                    continue 2;
                case 'cy':
                case 'crop-y':
                    $this->setCropY($v);
                    continue 2;
            }
        }
    }

    protected function generateSourcePath($path)
    {
        $this->setSourcePath(implode('/', [$this->getRootPath(), $path]));
    }

    public function getHtml()
    {
        $this->addAttribute('src', $this->getUrl());
        return '<img'.$this->getAttributesString().'>';
    }

    public function outdated()
    {
        if ( ! is_file($this->getNewPath()))
        {
            return TRUE;
        }
        return filemtime($this->getNewPath()) < filemtime($this->getSourcePath());
    }

    public function getNewPath()
    {
        return implode('/', [$this->getRootPath(), $this->getUri()]);
    }

    public function getSourcePath()
    {
        return $this->source_path;
    }

    protected function setSourcePath($source_path)
    {
        if ( ! is_file($source_path))
        {
            throw new Exception\InvalidImagePathException("Invalid image: $source_path", 1);
        }
        $this->source_path = $source_path;
    }

    protected function setSize($size)
    {
        if ( ! array_key_exists($size, $image_sizes = $this->getConfigItem('images-sizes')))
        {
            throw new Exception\InvalidImageArgumentException("Size $size is an unknown image-size", 1);
        }
        $this->setResizeWidth($image_sizes[$size][0]);
        $this->setResizeHeight($image_sizes[$size][1]);
    }

    protected function setManipulationValue($k, $v)
    {
        if (array_key_exists($v, $image_sizes = $this->getConfigItem('images-sizes')))
        {
            $key = strstr($k, 'width') ? 0 : 1;
            return $this->setManipulationValue($k, $image_sizes[$v][$key]);
        }
        $this->manipulation[$k] = $v;
        return $this;
    }

    protected function getManipulationValue($k)
    {
        if (array_key_exists($k, $this->manipulation))
        {
            return $this->manipulation[$k];
        }
        return NULL;
    }

    public function getMultiplier()
    {
        return $this->getManipulationValue('multiplier') ?: 1;
    }

    public function getResizeWidth()
    {
        return $this->getArg($this->getManipulationValue('width'));
    }

    public function getResizeHeight()
    {
        return $this->getArg($this->getManipulationValue('height'));
    }

    public function getCropWidth()
    {
        return $this->getArg($this->getManipulationValue('crop-width'));
    }

    public function getCropHeight()
    {
        return $this->getArg($this->getManipulationValue('crop-height'));
    }

    public function getCropX()
    {
        return $this->getArg($this->getManipulationValue('crop-x'));
    }

    public function getCropY()
    {
        return $this->getArg($this->getManipulationValue('crop-y'));
    }

    protected function setMultiplier($multiplier)
    {
        $this->setManipulationValue('multiplier', $multiplier);
    }

    protected function setResizeWidth($width)
    {
        $this->addAttribute('width', $width, FALSE);
        $this->setManipulationValue('width', $width);
    }

    protected function setResizeHeight($height)
    {
        $this->addAttribute('height', $height, FALSE);
        $this->setManipulationValue('height', $height);
    }

    protected function setCropWidth($width)
    {
        $this->addAttribute('width', $width);
        $this->setManipulationValue('crop-width', $width);
    }

    protected function setCropHeight($height)
    {
        $this->addAttribute('height', $height);
        $this->setManipulationValue('crop-height', $height);
    }

    protected function setCropX($x)
    {
        $this->setManipulationValue('crop-x', $x);
    }

    protected function setCropY($y)
    {
        $this->setManipulationValue('crop-y', $y);
    }

    protected function getArg($arg)
    {
        if ($arg)
        {
            return $arg * $this->getMultiplier();
        }
        return $arg;
    }

}