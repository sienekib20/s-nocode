<?php

namespace Mehael\Kernel;

use Gbs\Kibo\Anotation\Route;
use Mehael\Http\Request;
use Mehael\Http\Response;
use Mehael\Exceptions\Rendering\Whoops;

class Application
{	
	public ?string $name = null;
	protected Request $request;
	protected Response $response;
	protected Route $route;
	protected $whoops = null;

	public function __construct()
	{
		$this->name = basename(abs_path());
		$this->whoops = Whoops::lookUp();

		$this->request = new Request();
		$this->response = new Response();
		$this->route = new Route($this->request, $this->response);
	}

	public function run()
	{
		try {

			// executa as rotas

			$this->route->resolve();

		} catch (\Exception $e) {
			$this->whoops->handleException($e);
		}
	}

}