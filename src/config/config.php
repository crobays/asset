<?php

return [
	
	'url' => '//assets-www.dev.example.com',

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
		'small' => [240, NULL],
		'medium' => [480, NULL],
		'large' => [960, NULL],
		'xlarge' => [1440, NULL],
		'xxlarge' => [2560, NULL],
        '100%' => ['xlarge', NULL],
	],
];