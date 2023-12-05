<?php

namespace Gbs\Kibo;

class Dispatcher
{
	public function dispatch($request, $route, $matches)
	{
		$request->bind($matches);

		if ($request->method() == $route->method) {
			unset($route->method);

			if (!empty(($middleware = $route->middleware))) {
				foreach ($middleware as $dir => $target) {
					$targetMiddleware = "App\\Http\\Middlewares\\".ucfirst($dir)."\\".ucfirst($target);
					if (class_exists($targetMiddleware)) {
						if (call_user_func_array([new $targetMiddleware, 'execute'], [])) {
							continue; // Futuramente se der problemas vou ver aqui
						}
					}
					die ("Unknown middleware $target");
				}
			}

			if (is_callable($callback = $route->action)) {
				return call_user_func($callback, $request);
			}

			if (is_array($callback)) {
				if (class_exists($callback[0])) {
					$callback[0] = new $callback[0]();
					if (method_exists($callback[0], $callback[1])) {
						if ($middleware != null) {
							call_user_func_array([new $middleware, 'handle'], []);
							$middleware = null;
						}

						return call_user_func($callback, $request);
					}
					die ('Controller method doesn\'t exists');
				}
				die ('Undefined controller');
			}
		}
		
		die ('Unknown route access method ' . $route->method);
	}
}