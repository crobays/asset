<?php namespace Crobays\Asset\Image;

use Crobays\Asset\Exception;

class Image extends \Crobays\Asset\Asset {

    protected $type = 'image';

    protected $attributes = [
        'width' => NULL,
        'height' => NULL,
        'class' => 'image',
        'src' => FALSE,
        'data-src2x' => FALSE,
    ];

    protected $image_url;

    protected $sizes;

    public function __construct(\Illuminate\Config\Repository $config, \Crobays\Asset\Image\ImageUrl $img_url)
    {
        parent::__construct($config);
        $this->image_url = $img_url;
        $this->image_url->setBaseUrl($this->config->get('asset::url'));
        $this->image_url->setRootPath($this->config->get('asset::root-path'));
        $this->image_url->setFileArgumentsSeperator($this->config->get('asset::file-arguments-seperator'));
        $this->image_url->setArgumentSeperator($this->config->get('asset::argument-seperator'));
        $this->image_url->setSourceDir($this->config->get('asset::images-directories.'.$this->type.'.source'));
        $this->image_url->setUriDir($this->config->get('asset::images-directories.'.$this->type.'.uri'));
    }

    public function url()
    {
        return $this->image_url->url();
    }

    public function html()
    {
        $this->setAttribute('src', $this->image_url->url());
        $this->setAttribute('data-src2x', $this->image_url->setMultiplier(2)->url());
        return '<img'.$this->attributesString().'>';
    }

    /**
     * Set image parameters
     *
     * @return string
     */
    public function addParams(array $params)
    {
        foreach($params as $key => $value)
        {
            $this->addParam($key, $value);
        }
        return $this;
    }

    /**
     * Set image param
     *
     * @return string
     */
    public function addParam($key, $value)
    {
        switch ($key) {
            case 's':
            case 'size':
                $this->setSize($value);
                continue;
            case 'w':
            case 'width':
                $value = $this->resolveArg('width', $value, TRUE);
                $this->setAttribute('width', $value, FALSE);
                $value = $this->resolveArg('width', $value);
                $this->image_url->setWidth($value);
                continue;
            case 'h':
            case 'height':
                $value = $this->resolveArg('height', $value, TRUE);
                $this->setAttribute('height', $value, FALSE);
                $value = $this->resolveArg('height', $value);
                $this->image_url->setHeight($value);
                continue;
            case 'cw':
            case 'crop-width':
                $value = $this->resolveArg('width', $value, TRUE);
                $this->setAttribute('width', $value);
                $value = $this->resolveArg('width', $value);
                $this->image_url->setCropWidth($value);
                continue;
            case 'ch':
            case 'crop-height':
                $value = $this->resolveArg('height', $value, TRUE);
                $this->setAttribute('height', $value);
                $value = $this->resolveArg('height', $value);
                $this->image_url->setCropHeight($value);
                continue;
            case 'cx':
            case 'crop-x':
                $this->image_url->setCropX($value);
                continue;
            case 'cy':
            case 'crop-y':
                $this->image_url->setCropY($value);
                continue;
            default:
                $this->setAttribute($key, $value);
        }
        return $this;
    }

    protected function setSize($size)
    {
        if ( ! array_key_exists($size, $sizes = $this->sizes()))
        {
            throw new Exception\InvalidImageArgumentException("Size $size is an unknown image-size, choose ".implode(', ', array_keys($sizes)), 1);
        }

        $this->addParams([
            'width' => $sizes[$size][0],
            'height' => $sizes[$size][1],
        ]);
        return $this;
    }

    protected function sizes()
    {
        return $this->config->get('asset::images-sizes');
    }

    protected function resolveArg($k, $v, $allow_percentage = FALSE)
    {
        if ($allow_percentage && substr($v, -1, 1) == '%')
        {
            return $v;
        }

        if (array_key_exists($v, $sizes = $this->sizes()))
        {
            $key = strstr($k, 'width') ? 0 : 1;
            return $this->resolveArg($k, $sizes[$v][$key]);
        }
        return $v;
    }

}