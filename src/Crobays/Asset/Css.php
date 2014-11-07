<?php namespace Crobays\Asset;

class Css extends Asset {

	protected $attributes = [
		'type' => 'text/css',
    	'href' => FALSE,
		'rel' => 'stylesheet',
	];

    /**
     * Get the CSS file
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri ?: $this->fetchPath($this->getConfigItem('css'));
    }

    public function getHtml()
    {
    	$this->addAttribute('href', $this->getUrl());
    	return '<link'.$this->getAttributesString().'>';
    }
}