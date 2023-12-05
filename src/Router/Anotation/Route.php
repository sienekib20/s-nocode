<?php

namespace Gbs\Kibo\Anotation;

use Mehael\Http\Request;
use Mehael\Http\Response;
use Gbs\Kibo\Dispatcher;
use Gbs\Kibo\Regex\RegexChecker as Checker;
use Gbs\Kibo\Regex\RegexBuilder as Regex;

class Route extends Dispatcher
{
	private static array $routes = [];
	private static array $middleware = [];
	private Request $request;
	private Response $response;

	public function __construct(Request $request, Response $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	public static function add(string $method, string $uri, callable|array $action)
	{
		static::$routes[] = (object) [
			'method' => $method, 'uri' => $uri, 'action' => $action,
			'middleware' => static::$middleware
		];
	}

	public static function group(array $middleware, callable $callback)
	{
		foreach ($middleware as $key => $value) {
			static::$middleware[$key] = $value;
		}
		$callback();
		static::$middleware = [];
	}

	public function resolve()
	{
		foreach (static::$routes as $route) {
			$pattern = (Checker::findWildcard($route->uri)) 
				? ($regex = Regex::buildRegexRoutePattern($route->uri))['pattern']
				: "/^".str_replace('/', '\/', $route->uri)."$/";
			unset($route->uri);
			if (preg_match($pattern, $this->request->uri(), $matches)) {
				$matches = array_slice($matches, 1);
				foreach ($matches as $key => $value) {
					$matches[$regex['param'][$key]] = $value;
					unset($matches[$key]);
				}

				$middleware = static::$middleware;

				return $this->dispatch($this->request, $route, $matches);
			}
		}
		die ('Route not found');
	}
}