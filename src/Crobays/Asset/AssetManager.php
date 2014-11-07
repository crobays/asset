<?php namespace Crobays\Asset;

class AssetManager
{
    protected $config = array();

    /**
     * Get the assets domain
     *
     * @var Url
     * @return string
     */
    public function __construct(array $config = array())
    {
        $this->configure(require(__DIR__.'/../../config/config.php'));
        $this->configure($config);
    }

    /**
     * Configure
     * @param array config
     * @return this
     */
    public function configure(array $config = array())
    {
        $this->config = array_replace($this->config, $config);
        return $this;
    }

    /**
     * Get the assets domain
     *
     * @var string
     * @return string
     */
    public function domain()
    {
        $asset = new Asset($this->config);
        return $asset->getDomain();
    }

    /**
     * Get the assets domain
     *
     * @var string
     * @return string
     */
    public function url($uri = NULL)
    {
        $asset = new Asset($this->config);
        if( ! is_null($uri))
        {
            $asset->setUri($uri);
        }
        return $asset->getUrl();
    }

    // /**
    //  * Get the assets domain
    //  *
    //  * @var string
    //  * @return string
    //  */
    // public function makeImage($path)
    // {
    //     $img = new Image;
    //     $img->setPath($path);
    //     if ($img->inCache())
    //     {
    //         return $img->getCached();
    //     }
    //     return $img->make();
    // }

    // /**
    //  * Get a full image url
    //  *
    //  * @var string
    //  * @return string
    //  */
    // public function imgUrl($file, $params = array())
    // {
    //     $img = new Image\Image();
    //     $img->setFile($file);
    //     $img->setParams($params);
    //     return $img->getUrl();
    // }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function img($file, $params = array())
    {
        $img = new Image\Image($this->config);
        $img->setUri($file);
        $img->setParams($params);
        $img->make();
        return $img->getHtml();
    }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function pic($file, $params = array())
    {
        $img = new Image\Picture($this->config);
        $img->setUri($file);
        $img->setParams($params);
        $img->make();
        return $img->getHtml();
    }

    // /**
    //  * Get a full picture url
    //  *
    //  * @var string
    //  * @return string
    //  */
    // public function picUrl($file, $params = array())
    // {
    //     $pic = new Image\Picture();
    //     $pic->setFile($file);
    //     $pic->setParams($params);
    //     return $pic->getUrl();
    // }

    /**
     * Get a full file url
     *
     * @var string
     * @return string
     */
    public function fileUrl($file, $params = array())
    {
        $file = new File($this->config);
        $file->setFile($file);
        $file->setParams($params);
        return $file->getUrl();
    }

    /**
     * Get a full CSS url
     *
     * @var string
     * @return string
     */
    public function css($file = NULL, $params = array())
    {
        $css = new Css($this->config);
        if ( ! is_null($file))
        {
            $css->setFile($file);
        }
        $css->setParams($params);
        return $css->getHtml();
    }

    /**
     * Get a full Javascript url
     *
     * @var string
     * @return string
     */
    public function js($file = NULL, $params = array())
    {
        $js = new Js($this->config);
        if ( ! is_null($file))
        {
            $js->setFile($file);
        }
        $js->setParams($params);
        return $js->getHtml();
    }
}