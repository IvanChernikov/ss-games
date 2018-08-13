<?php

function load($path) {
	$file = sprintf('%s/vendor/%s', __DIR__ ,$path);
	return file_get_contents($file);
}

function save($type, $data) {
	$file = sprintf('%s/required.%s', $type, $type);
	return file_put_contents($file, $data);
}

$required = [
	'js' => [
		'components/jquery/jquery.min.js',
		'components/jqueryui/jquery-ui.min.js',
		'twbs/bootstrap/dist/js/bootstrap.bundle.min.js'
	],
	'css' => [
		'twbs/bootstrap/dist/css/bootstrap.min.css',
		'components/jqueryui/themes/smoothness/jquery-ui.min.css',
		'components/jqueryui/themes/smoothness/theme.css',
		'components/font-awesome/css/fontawesome-all.min.css'
	]
];

$copy = [
	'webfonts' => [
		'components/font-awesome/webfonts/fa-brands-400.woff2',
		'components/font-awesome/webfonts/fa-regular-400.woff2',
		'components/font-awesome/webfonts/fa-solid-900.woff2'
	]
];

foreach ($required as $type => $requirements) {
	$files = array_map('load', $requirements);
	$data = implode('', $files);
	save($type, $data);
}

foreach ($copy as $folder => $files) {
	foreach ($files as $file) {
		$path = sprintf('%s%s%s', $folder, DIRECTORY_SEPARATOR, basename($file));
		file_put_contents($path, load($file));
	}
}