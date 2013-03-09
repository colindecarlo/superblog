<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->get('/', function () {
	return "This will be a blog.";
});

$app->run();
