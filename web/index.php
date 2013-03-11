<?php

use Symfony\Component\HttpFoundation\Request;

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

// register the doctrin service provider
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
	'db.options' => [
		'driver' => 'pdo_mysql',
		'dbname' => 'superblog',
		'host' => 'localhost',
		'user' => 'blogist',
		'password' => 'blogistpass',
		'charset' => 'utf8',
	]
]);

$app->get('/', function () use ($app) {
	$repo = new SB\Repo\Post($app['db']);
	$posts = $repo->getPosts();

	return $app['twig']->render('home.twig', ['posts' => $posts]);
});

$app->get('/login', function (Request $request) use ($app) {
	return $app['twig']->render('login.twig', [
		'error' => $app['security.last_error']($request),
		'last_username' => $app['session']->get('_security.last_username'),
	]);
});

$app->get('/admin/new', function () use ($app) {
	return $app['twig']->render('admin/new.twig', ['post' => ['title' => '', 'content' => '']]);
});

$app->post('/admin/new', function (Request $request) use ($app) {
	$post = [
		'title' => $request->request->get('title'),
		'content'=> $request->request->get('content')
	];

	$repo = new SB\Repo\Post($app['db']);
	$repo->insert($post);

	return $app->redirect('/');
});

$app->run();
