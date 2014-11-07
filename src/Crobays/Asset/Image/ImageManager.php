<?php namespace Crobays\Asset\Image;

use Closure;

class ImageManager
{
    /**
     * Config
     *
     * @var array
     */
    public $config = array();

    /**
     * Config
     *
     * @var array
     */
    public $image;


    /**
     * Creates new instance of Image Manager
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->configure(require(__DIR__.'/../../../config/config.php'));
        $this->configure($config);
    }

    /**
     * Overrides configuration settings
     *
     * @param array $config
     */
    public function configure(array $config = array())
    {
        $this->config = array_replace($this->config, $config);

        return $this;
    }

    /**
     * Initiates an Image instance from different input types
     *
     * @param  string uri
     *
     * @return \Intervention\Image\Image
     */
    public function make($uri)
    {
        return $this->createImage()->setUri($uri)->make();
    }

    public function createImage()
    {
        $this->image = new Image;
        $this->image->setConfig($this->config);
        return $this->image;
    }

    public function getImage()
    {
        return $this->image;
    }

}
