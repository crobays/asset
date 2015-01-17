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
        return $this->uri ?: $this->fetchPath($this->config->get('asset::css'));
    }

    /**
     * Get the HTML link element with
     *
     * @return string
     */
    public function html()
    {
    	$this->addAttribute('href', $this->url());
    	return '<link'.$this->attributesString().'>';
    }
}