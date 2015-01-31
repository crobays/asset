<?php namespace Crobays\Asset\Image;

class Picture extends Image {

    protected $type = 'picture';

    protected $attributes = [
        'width' => NULL,
        'height' => NULL,
        'class' => NULL,
    ];

    protected function defaultSourceAttribute()
    {
        return $this->config->get('asset::default-pic-src-attr') ?: parent::defaultSourceAttribute();
    }

    protected function addDefaultClasses()
    {
    	if ($classes = $this->config->get('asset::picture-classes'))
    	{
    		$this->addParam('class', $classes);
    	}
    }

}