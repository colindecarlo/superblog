<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// register the twig service provider
$app->register(new Silex\Provider\TwigServiceProvider(),
	['twig.path' => __DIR__ . '/../views']
);

$app->get('/', function () use ($app) {
	return $app['twig']->render('home.twig');
});

$app->run();