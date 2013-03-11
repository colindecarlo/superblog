<?php

namespace SB\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class RepoServiceProvider implements ServiceProviderInterface
{
	public function register(Application $app)
	{
	}

	public function boot(Application $app)
	{
		$namespace = 'SB\Repo';
		foreach ($app['repo.classes'] as $className) {
			$fqn = $namespace . '\\' . $this->_inflect($className);
			if (class_exists($fqn)) {
				$app['repo.' . $className] = $app->share(function () use ($app, $fqn) {
					return new $fqn($app['repo.connection']);
				});
			}
		}
	}

	protected function _inflect($className)
	{
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $className)));
	}
}
