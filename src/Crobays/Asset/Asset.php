<?php namespace Crobays\Asset;

class Asset {

	protected $config = array();

	protected $attributes = array();

	protected $uri;

	/**
     * Get the assets domain
     *
     * @var Url
     * @return string
     */
    public function __construct(array $config = array())
    {
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
     * Get the assets domain.
     *
     * @return string
     */
    public function getDomain()
    {
        $domain = $this->getUrl();
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
    public function getUrl()
    {
        if ($uri = $this->getUri())
        {
            return implode('/', [$this->getBaseUrl(), $uri]);
        }
        return $this->getBaseUrl();
    }

    /**
     * Set the assets url
     * @param string url
     * @return this
     */
    public function setUrl($url)
    {
        if ( ! strstr($url, '//'))
        {
            $url = "//$url";
        }
        $this->url = rtrim($url, '/');
        return $this;
    }

    /**
     * Configure
     * @param string key
     * @return this
     */
    protected function getConfigItem($key)
    {
        if ( ! array_key_exists($key, $this->config))
        {
            throw new Exception\InvalidConfigItemException("The following config item does not exist: $key", 1);
        }
        return $this->config[$key];
    }

    /**
     * Set the assets url
     * @return string
     */
    public function getBaseUrl()
    {
        if ( ! strstr($url = $this->getConfigItem('url'), '//'))
        {
            $url = "//$url";
        }
        return rtrim($url, '/');
    }

    /**
     * Set the file
     *
     * @param string
     * @return void
     */
    public function setUri($uri)
    {
        $this->uri = $this->fetchPath($uri);
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
            $this->addAttribute($k, $v);
        }
        return $this;
    }

    /**
     * Set html attributes
     *
     * @return string
     */
    public function addAttribute($attr, $val, $overwrite = TRUE)
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
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get html attributes in string
     *
     * @return string
     */
    public function getAttributesString()
    {
        $attrs = array();
        foreach($this->getAttributes() as $attr => $val)
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
    public function setParams(array $params)
    {
        $this->addAttributes($params);
    }

    /**
     * Get the file name
     *
     * @return string
     */
    function getFileName()
    {
        return basename($this->uri);
    }

    /**
     * Get the file name base
     *
     * @return string
     */
    public function getFileNameBase()
    {
        $base = strrev(strstr(strrev($this->getFileName()), '.'));
        return substr($base, 0, strlen($base) - 1);
    }

    /**
     * Get the extension
     *
     * @return string
     */
    public function getExtension()
    {
        return strtolower(trim(strrchr($this->getFileName(), '.'), '.'));
    }

    public function getUri()
    {
    	if ( ! $this->uri)
    	{
    		return '';
    	}

    	$dir = dirname($this->uri);
        return $this->fetchPath($dir, $this->getFileNameBase().'.'.$this->getExtension());
    }

    protected function fetchPath()
    {
        $path = implode('/', func_get_args());
        return trim(preg_replace('/\/+/', '/', $path), '/');
    }
}