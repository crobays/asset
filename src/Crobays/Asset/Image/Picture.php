<?php namespace Crobays\Asset\Image;

class Picture extends Image {

    protected $type = 'picture';

    protected $attributes = [
        'width' => NULL,
        'height' => NULL,
        'class' => 'picture',
        'src' => FALSE,
    ];

}