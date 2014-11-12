<?php namespace Crobays\Asset;

class Asset {

	protected $config;

	protected $attributes = array();

	protected $uri;

    protected $base_url;

	/**
     * Get the assets domain
     *
     * @var Url
     * @return string
     */
    public function __construct(\Illuminate\Config\Repository $config)
    {
        $this->config = $config;
    }

	/**
     * Get the assets domain.
     *
     * @return string
     */
    public function domain()
    {
        $domain = $this->baseUrl();
        if ($stripped = strstr($domain, '//'))
        {
            $domain = substr($stripped, 2);
        }
        return trim($domain, '/');
    }

    /**
     * Get the assets url
     *
     * @return string
     */
    public function url()
    {
        if ($uri = $this->uri())
        {
            return $this->fetchPath($this->baseUrl(), $uri);
        }
        return $this->baseUrl();
    }

    // /**
    //  * Set the assets url
    //  * @param string url
    //  * @return this
    //  */
    // public function setUrl($url)
    // {
    //     if ( ! strstr($url, '//'))
    //     {
    //         $url = "//$url";
    //     }
    //     $this->base_url = rtrim($url, '/');
    //     return $this;
    // }

    

    /**
     * Get the asset url
     * @return string
     */
    public function baseUrl()
    {
        if (is_null($this->base_url))
        {
            $this->setBaseUrl($this->config->get('asset::url'));
        } 

        return $this->base_url;
    }

    /**
     * Set the asset url
     * @return string
     */
    public function setBaseUrl($url)
    {
        if ( ! strstr($url, '//'))
        {
            $url = "//$url";
        }
        $this->base_url = rtrim($url, '/');
        return $this;
    }

    /**
     * Set the uri
     *
     * @param string
     * @return void
     */
    public function setFile($file)
    {
        if (preg_match('/(http(s)?:)?\/\/[^\/]*/', $file, $match))
        {
            $url = $match[0];
            $uri = substr($file, strlen($url));
            $this->setBaseUrl($url);
            $this->setUri($uri);
            return $this;
        }

        $this->setUri($file);
        return $this;
    }

    /**
     * Set the uri
     *
     * @param string
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = $this->fetchPath($uri);
    }

    /**
     * Get the uri
     *
     * @return string
     */
    public function uri()
    {
        return $this->uri;
        // if ( ! $this->uri)
        // {
        //     return '';
        // }

        // $dir = dirname($this->uri);
        // return $this->fetchPath($dir, $this->fileNameBase().'.'.$this->extension());
    }

    /**
     * Set html attributes
     *
     * @return string
     */
    public function addAttributes(array $params)
    {
        foreach($params as $k => $v)
        {
            $this->setAttribute($k, $v);
        }
        return $this;
    }

    /**
     * Set html attributes
     *
     * @return string
     */
    public function setAttribute($attr, $val, $overwrite = TRUE)
    {
        if( ! $overwrite && array_key_exists($attr, $this->attributes) && ! is_null($this->attributes[$attr]))
        {
            return $this;
        }
        $this->attributes[$attr] = is_array($val) ? implode(' ', $val) : $val;
        return $this;
    }

    /**
     * Get html attributes
     *
     * @return string
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Get html attributes in string
     *
     * @return string
     */
    public function attributesString()
    {
        $attrs = array();
        foreach($this->attributes() as $attr => $val)
        {
        	if (is_null($val))
        	{
        		continue;
        	}
            $attrs[] = "$attr=\"$val\"";
        }
        return $attrs ? ' '.implode(' ', $attrs) : '';
    }

    /**
     * Set image manipulation parameters
     *
     * @return string
     */
    public function addParams(array $params)
    {
        $this->addAttributes($params);
    }

    // /**
    //  * Get the file name
    //  *
    //  * @return string
    //  */
    // function fileName()
    // {
    //     return basename($this->uri);
    // }

    // /**
    //  * Get the file name base
    //  *
    //  * @return string
    //  */
    // public function fileNameBase()
    // {
    //     $base = strrev(strstr(strrev($this->fileName()), '.'));
    //     return substr($base, 0, strlen($base) - 1);
    // }

    // /**
    //  * Get the extension
    //  *
    //  * @return string
    //  */
    // public function extension()
    // {
    //     return strtolower(trim(strrchr($this->fileName(), '.'), '.'));
    // }

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