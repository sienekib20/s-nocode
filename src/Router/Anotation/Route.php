<?php

namespace Sienekib\Mehael\Router\Anotation;

use Sienekib\Mehael\Http\Request;
use Sienekib\Mehael\Http\Response;
use Sienekib\Mehael\Router\Dispatcher;
use Sienekib\Mehael\Router\Src\Wildcards;

class Route
{
    use Dispatcher;

    /**
     * Todas as rotas do app
     * @var array
     */
    private static array $routes = [];

    /**
     * Middleware de agrupamento
     * @var string
     */
    private static ?string $middleware = null;

    /**
     * Prefixo de rotas agrupadas
     * @var string
     */
    private static ?string $prefix = null;

    /**
     * Parâmetros da requisição
     * @var array
     */
    private static array $parameters = [];

    /**
     * Patterna de regex da uri
     * @var string
     */
    private static string $pattern;

    /**
     * Objeto request
     * @var Request
     */
    private static Request $request;

    /**
     * Objeto response
     * @var Response
     */
    private static Response $response;

    /**
     * Constructor de inicialização da classe Route
     */
    public function __construct(Request $request, Response $response)
    {
        static::$request = $request;
        static::$response = $response;
    }

    /**
     * Regista as rotas no array
     */
    public static function add($method, $uri, $action)
    {
        $uri = (!is_null(static::$prefix)) ? '/'.static::$prefix.$uri : $uri;

        static::$routes[] = (object) [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'prefix' => static::$prefix,
            'middleware' => static::$middleware
        ];


        /*static::$routes[$method][$uri] = (object) [
            'action' => $action,
            'prefix' => static::$prefix,
            'middleware' => static::$middleware
        ];*/
    }

    public static function get($uri, $action)
    {
        static::add('GET', $uri, $action);
    }

    public static function post($uri, $action)
    {
        static::add('POST', $uri, $action);
    }

    /**
     * Define um prefixo às rotas
     * @return Route
     */
    public static function prefix($prefix): Route
    {
        static::$prefix = $prefix;

        return new static(new Request, new Response);
    }

    /**
     * Define um agrupamento de rotas
     * atribuindo-as um middleware
     */
    public static function group($middleware, $callback)
    {
        static::$middleware = $middleware;

        // Verifica se é uma funcao 
        if (is_callable($callback)) {
            $callback();
        }

        static::$middleware = static::$prefix = null;
    }

    /**
     * Patterna de regex da uri
     */
    public function dispatch()
    {
        $found = -1;
        $positionRoute = -1;
        $key = 0;

        foreach (static::$routes as $index => $route) {
            $uri = preg_replace('/\/{(\w+)}/', '/(?<$1>.*?)', $route->uri);
            $uri = "#^" . $uri . "$#";

            if (preg_match($uri, static::$request->uri(), $matches)) {
                $matches = array_slice($matches, 1);
                //$parameters = $this->routeParameters($parameters, $matches);
                $parameters = [];
                foreach ($matches as $key => $value) {
                    if (gettype($key) == 'integer') {
                        continue;
                    }
                    $parameters[$key] = $value;
                }

                $callback = static::$routes[$route->method][$route->uri] ?? false;

                //dd($callback);

                if (static::$request->method() == $route->method) {
                    unset($route->method, $route->uri, $route->prefix);
                    static::$request->bind($parameters);
                    $found++;
                    $positionRoute = $route;
                    break;
                }
            }
        }

        if ($found > -1) {
            //dd($positionRoute, $found);
            return $this->dispatchRoute($positionRoute, static::$request, static::$response);
        } else {

            echo 'Método de acesso não compatível';
            static::$response->setStatusCode(405);
            exit;
        }



        static::$response->setStatusCode(404);
        //throw new \Exception('Rota `' . static::$request->uri() . '` não encontrada');
        exit;
    }
}