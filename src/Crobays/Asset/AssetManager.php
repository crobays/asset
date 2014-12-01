<?php namespace Crobays\Asset;

use Html;

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
     * Get an image in html
     *
     * @var string
     * @return string
     */
    public function img($file, $params = array())
    {
        return $this->image($file, $params)->html();
    }

    /**
     * Get an image url
     *
     * @var string
     * @return string
     */
    public function imgUrl($file, $params = array())
    {
        return $this->image($file, $params)->url();
    }

    /**
     * Get an image object
     *
     * @var string
     * @return string
     */
    public function image($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $img = new Image\Image($this->config, $img_url);
        $img->addParams($params);
        return $img;
    }


    /**
     * Get a picture in html
     *
     * @var string
     * @return string
     */
    public function pic($file, $params = array())
    {
        return $this->picture($file, $params)->html();
    }

    /**
     * Get picture url
     *
     * @var string
     * @return string
     */
    public function picUrl($file, $params = array())
    {
        return $this->picture($file, $params)->url();
    }

    /**
     * Get a picture Object
     *
     * @var string
     * @return string
     */
    public function picture($file, $params = array())
    {
        $img_url = new Image\ImageUrl;
        $img_url->setFile($file);
        $pic = new Image\Picture($this->config, $img_url);
        $pic->addParams($params);
        return $pic;
    }

    /**
     * Get a favicon in html
     *
     * @var string
     * @return string
     */
    public function favicon($file, $size = 32)
    {
        $url = $this->image($file, ['w' => $size, 'h' => $size])->url();
        return "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"$url\"/>";
    }

    /**
     * Get a startup image in html
     *
     * @var string
     * @return string
     */
    public function startupImage($file, $size, $orientation, $multiplier = 1)
    {
        $media = array();
        if(is_string($size) && strstr($size, 'x'))
        {
            $size = explode('x', $size);
        }
        $width = $size[0];
        $height = $size[1];
        $w = $orientation == 'landscape' ? $size[1] : $size[0];
        $h = ($orientation == 'landscape' ? $size[0] : $size[1]) - 20;
        $url = $this->image($file, [
            'r' => $orientation == 'landscape' ? -90 : NULL,
            'w' => $w - 40,
            'cw' => $w,
            'ch' => $h,
            '@' => $multiplier,
        ])->url();
        
        //$url = str_replace('//assets-www.dev.automakelaaraanhuis.nl/img', '', $url);
        array_push($media, "(device-width: ${width}px)");
        array_push($media, "(device-height: ${height}px)");
        array_push($media, "(orientation: $orientation)");
        array_push($media, "(-webkit-device-pixel-ratio: $multiplier)");

        $media_string = implode(' and ', $media);

        return "<link rel=\"apple-touch-startup-image\" media=\"$media_string\" href=\"$url\">";
    }
    
    /**
     * Get a startup images in html
     *
     * @var string
     * @return string
     */
    public function startupImages($file)
    {
        $sizes = [
            [320, 480],
            [320, 568],
            [768, 1024],
        ];
        
        $startup_images = array();
        foreach($sizes as $size)
        {
            foreach(['portrait', 'landscape'] as $orientation)
            {
                foreach([1, 2] as $multiplier)
                {
                    array_push($startup_images, $this->startupImage($file, $size, $orientation, $multiplier));
                }
            }
        }
        return implode("\n", array_reverse($startup_images));
    }

    /**
     * Get a apple-touch-icon-precomposed in html
     *
     * @var string
     * @return string
     */
    public function touchIcon($file, $size = 72)
    {
        $url = $this->image($file, ['w' => $size-10, 'h' => $size-10, 'cw' => $size, 'ch' => $size])->url();
        return "<link rel=\"apple-touch-icon\" href=\"$url\" sizes=\"${size}x${size}\">";
    }

    /**
     * Get a all apple-touch-icon-precomposed sizes in html
     *
     * @var string
     * @return string
     */
    public function touchIcons($file)
    {
        $icons = array();
        foreach([57, 72, 76, 114, 120, 144, 152] as $size)
        {
            array_push($icons, $this->touchIcon($file, $size));
        }
        
        return implode("\n", $icons);
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