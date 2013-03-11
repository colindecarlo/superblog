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

// register the Repo Service Provider
$app->register(new SB\Provider\RepoServiceProvider(), [
	'repo.classes' => ['post', 'comment'],
	'repo.connection' => $app['db']
]);

$app->get('/', function () use ($app) {
	$posts = $app['repo.post']->getPosts();
	$counts = $app['repo.comment']->getCommentCounts();

	foreach ($posts as &$post) {
		$postId = $post['post_id'];
		$post['comment_count'] = 0;
		if (isset($counts[$postId])) {
			$post['comment_count'] = $counts[$postId];
		}
	}

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

	$app['repo.post']->insert($post);

	return $app->redirect('/');
});

$app->get('/read/{postId}', function ($postId) use ($app) {
	$post = $app['repo.post']->getPost($postId);
	$comments = $app['repo.comment']->getCommentsForPost($postId);

	if (empty($post)) {
		$app->abort(404, 'Not Found');
	}

	return $app['twig']->render('read.twig', [
		'post' => $post,
		'comments' => $comments,
	]);
})
->assert('postId', '\d+');

$app->post('/comment', function (Request $request) use ($app) {
	$comment = [
		'post_id' => $request->request->get('post_id'),
		'email' => $request->request->get('email'),
		'comment' => $request->request->get('comment'),
	];

	$app['repo.comment']->insert($comment);

	return $app->redirect('/read/' . $comment['post_id']);
});

$app->run();
