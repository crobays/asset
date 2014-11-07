<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;

class Image extends \Crobays\Asset\Asset {

    public $image_directory_key = 'image';

    public $uri_args = [
        'w' => NULL,
        'h' => NULL,
        'cw' => NULL,
        'ch' => NULL,
        'cx' => NULL,
        'cy' => NULL,
    ];

    public $attributes = [
        'width' => NULL,
        'height' => NULL,
        'src' => FALSE,
    ];

    protected $generator;

    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->generator = new ImageGenerator;
    }

    public function setUri($uri)
    {
        $dirs = $this->configItem($key_1 = 'images-directories');
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

        $source_dir = $dirs[$this->image_directory_key]['source'];
        $uri_base = $dirs[$this->image_directory_key]['uri'];
        
        $source_path = $this->fetchPath($this->rootPath(), $source_dir, $uri);
        $this->uri = $this->fetchPath($uri_base, $uri);

        $this->generator->setSourcePath($source_path);
        return $this;
    }

    /**
     * Get the image directory
     *
     * @return string
     */
    public function fileNameBase()
    {
        $args = array();
        foreach ($this->uri_args as $key => $val)
        {
            if (is_null($val))
            {
                continue;
            }
            $args[] = $key.$val;
        }

        if ( ! $args || ! $this->generator->canBeManipulated())
        {
            return parent::fileNameBase();
        }
        return parent::fileNameBase().$this->configItem('arguments-seperator').implode($this->configItem('argument-seperator'), $args);
    }

    public function setUriArg($key, $val)
    {
        $this->uri_args[$key] = $val;
        return $this;
    }

    public function rootPath()
    {
        return $this->fetchPath($this->configItem('root-path'));
    }

    /**
     * Set image manipulation parameters
     *
     * @return string
     */
    public function addParams(array $params)
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
                    $v = $this->resolveArg('width', $v, TRUE);
                    $this->setAttribute('width', $v, FALSE);
                    $v = $this->resolveArg('width', $v);
                    $this->generator->setWidth($v);
                    $this->setUriArg('w', $v);
                    continue 2;
                case 'h':
                case 'height':
                    $v = $this->resolveArg('height', $v, TRUE);
                    $this->setAttribute('height', $v, FALSE);
                    $v = $this->resolveArg('height', $v);
                    $this->generator->setHeight($v);
                    $this->setUriArg('h', $v);
                    continue 2;
                case 'cw':
                case 'crop-width':
                    $v = $this->resolveArg('width', $v, TRUE);
                    $this->setAttribute('width', $v);
                    $v = $this->resolveArg('width', $v);
                    $this->generator->setCropWidth($v);
                    $this->setUriArg('cw', $v);
                    continue 2;
                case 'ch':
                case 'crop-height':
                    $v = $this->resolveArg('height', $v, TRUE);
                    $this->setAttribute('height', $v);
                    $v = $this->resolveArg('height', $v);
                    $this->generator->setCropHeight($v);
                    $this->setUriArg('ch', $v);
                    continue 2;
                case 'cx':
                case 'crop-x':
                    $this->generator->setCropX($v);
                    $this->setUriArg('cx', $v);
                    continue 2;
                case 'cy':
                case 'crop-y':
                    $this->generator->setCropY($v);
                    $this->setUriArg('cy', $v);
                    continue 2;
            }
        }
        return $this;
    }

    public function url()
    {
        $new_path = $this->fetchPath($this->rootPath(), $this->uri());
        $this->generator->setNewPath($new_path);
        $this->generator->make();
        return parent::url();
    }

    public function html()
    {
        $this->setAttribute('src', $this->url());
        return '<img'.$this->attributesString().'>';
    }

    protected function setSize($size)
    {
        if ( ! array_key_exists($size, $image_sizes = $this->configItem('images-sizes')))
        {
            throw new Exception\InvalidImageArgumentException("Size $size is an unknown image-size", 1);
        }

        $this->addParams([
            'width' => $image_sizes[$size][0],
            'height' => $image_sizes[$size][1],
        ]);
        return $this;
    }

    protected function resolveArg($k, $v, $allow_percentage = FALSE)
    {
        if ($allow_percentage && substr($v, -1, 1) == '%')
        {
            return $v;
        }

        if (array_key_exists($v, $image_sizes = $this->configItem('images-sizes')))
        {
            $key = strstr($k, 'width') ? 0 : 1;
            return $this->resolveArg($k, $image_sizes[$v][$key]);
        }
        return $v;
    }

}