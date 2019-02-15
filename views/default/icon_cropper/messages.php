<?php

// placeholder for messages
$errors = [];

$min_width = (int) elgg_get_plugin_setting('min_width', 'icon_cropper');
if ($min_width > 0) {
	$errors[] = elgg_format_element('span', [
		'class' => [
			'icon-cropper-error-width',
			'hidden',
		]
	], elgg_echo('icon_cropper:error:width', [$min_width]) . '&nbsp;');
}
$min_height = (int) elgg_get_plugin_setting('min_height', 'icon_cropper');
if ($min_height > 0) {
	$errors[] = elgg_format_element('span', [
		'class' => [
			'icon-cropper-error-height',
			'hidden',
		]
	], elgg_echo('icon_cropper:error:height', [$min_height]));
}

if (empty($errors)) {
	return;
}

// add generic message
array_unshift($errors, elgg_format_element('div', [
	'class' => [
		'icon-cropper-error-generic',
		'hidden',
	]
], elgg_echo('icon_cropper:error:generic')));

echo elgg_view_message('warning', implode(PHP_EOL, $errors), [
	'class' => ['icon-cropper-messages', 'hidden'],
]);
