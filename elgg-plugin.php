<?php

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

return [
	'views' => [
		'default' => [
			'cropperjs/cropper.js' => $composer_path . 'vendor/npm-asset/cropperjs/dist/cropper.min.js',
			'cropperjs/cropper.css' => $composer_path . 'vendor/npm-asset/cropperjs/dist/cropper.min.css',
		],
	],
];
