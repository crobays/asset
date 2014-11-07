<?php namespace Crobays\Asset;

class Js extends Asset {
    
    protected $attributes = [
		'type' => 'text/javascript',
    	'src' => FALSE,
	];

    /**
     * Get the Javascript file
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri ?: $this->fetchPath($this->getConfigItem('js'));
    }

    public function getHtml()
    {
    	$this->addAttribute('src', $this->getUrl());
    	return '<script'.$this->getAttributesString().'></script>';
    }
}