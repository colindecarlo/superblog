<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

// register the security and dependant providers
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), [
	'security.firewalls' => [
		'secured' => [
			'pattern' => '^/admin',
			'form' => ['login_path' => '/login', 'check_path' => '/admin/login_check'],
			'logout' => ['logout_path' => '/admin/logout'],
			'users' => [
				'admin' => ['ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==']
			],
		],
	],
]);

// register the twig service provider
$app->register(new Silex\Provider\TwigServiceProvider(),
	['twig.path' => __DIR__ . '/../views']
);

$app->get('/', function () use ($app) {
	return $app['twig']->render('home.twig');
});

$app->run();
