<?php namespace Crobays\Asset;

class AssetManager
{
    protected $config;

    // /**
    //  * Get the assets domain
    //  *
    //  * @var Url
    //  * @return string
    //  */
    // public function __construct()
    // {
    //     $this->configure(require(__DIR__.'/../../config/config.php'));
    // }

    // *
    //  * Configure
    //  * @param array config
    //  * @return this
     
    public function __construct(\Illuminate\Config\Repository $config)
    {
        $this->config = $config;
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
        return $asset->domain();
    }

    /**
     * Get the assets domain
     *
     * @var string
     * @return string
     */
    public function url($file = NULL)
    {
        $asset = new Asset($this->config);
        if( ! is_null($file))
        {
            $asset->setFile($file);
        }
        return $asset->url();
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
    //         return $img->cached();
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
    //     $img->addParams($params);
    //     return $img->url();
    // }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function img($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $img = new Image\Image($this->config, $img_url);
        $img->addParams($params);
        return $img->html();
    }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function imgUrl($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $img = new Image\Image($this->config, $img_url);
        $img->addParams($params);
        return $img->url();
    }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function pic($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $pic = new Image\Picture($this->config, $img_url);
        $pic->addParams($params);
        return $pic->html();
    }

    /**
     * Get a full image url
     *
     * @var string
     * @return string
     */
    public function picUrl($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $pic = new Image\Picture($this->config, $img_url);
        $pic->addParams($params);
        return $pic->url();
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
    //     $pic->addParams($params);
    //     return $pic->url();
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
        $file->addParams($params);
        return $file->url();
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
        $css->addParams($params);
        return $css->html();
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
        $js->addParams($params);
        return $js->html();
    }
}