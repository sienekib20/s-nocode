<?php

namespace core\classes;

use core\support\Check;
use core\support\Str;
use Exception;

class Route
{
    private $routes = [];

    public function addRoute($path, $action)
    {
        $this->routes[$path] = $action;
    }

    public function dispatch()
    {
        try {
            $routes = $this->routes;

            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            foreach ($routes as $key => $route) {

                $pattern = Str::generatePatternOf($key);

                if (preg_match($pattern, $uri, $matches)) {

                    list($controller, $method) = $route;

                    $instatiatedController = Check::despatchClassIfExists($controller);

                    if (!method_exists($instatiatedController, $method)) {

                        $controllerName = explode('\\', $controller);
                        $controllerName = end($controllerName);

                        throw new Exception("Method {$method} Not Found in Controller {$controllerName}", 1);
                    }

                    $params =  Str::changeAllParamsIndex(array_values(array_slice($matches, 1)));

                    return $instatiatedController->$method($params);
                }
            }

            throw new Exception("Route {$uri} Not Found", 1);
        } catch (Exception $ex) {

            response()->setHttpResponseCode(404);

            die('Erro: ' . $ex->getMessage());
        }
    }
}
