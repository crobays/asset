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
    public function uri()
    {
        return $this->uri ?: $this->fetchPath($this->configItem('css'));
    }

    public function html()
    {
    	$this->setAttribute('href', $this->url());
    	return '<link'.$this->attributesString().'>';
    }
}