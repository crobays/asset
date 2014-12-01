<?php

return [
	
	'url' => 'http://assets-www.example.com',

	'images-directories' => [
		'picture' => [
			'source' => 'pictures',
			'uri' => 'pic',
		],
		'image' => [
			'source' => 'images',
			'uri' => 'img',
		],
	],

	'file' => 'files',

	'css' => 'style.css',

	'js' => 'script.js',
	
	'root-path' => '.',

	'file-arguments-seperator' => '___',

	'argument-seperator' => '-',

	'images-sizes' => [
		'tiny' => [50, NULL],
		'small' => [150, NULL],
		'medium' => [400, NULL],
		'large' => [800, NULL],
		'xlarge' => [960, NULL],
		'xxlarge' => [1440, NULL],
        '100%' => ['large', NULL],
	],
];