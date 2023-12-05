<?php

/*use app\controllers\AppController;
use app\controllers\Editor;
use app\controllers\Templates;
use core\classes\Route;*/

use Gbs\Kibo\Anotation\Route;

Route::add('GET', '/login/(id:numeric)', function() {
	echo 'logando'; exit;
});

Route::add('GET', '/', function() {
	echo 'Aqui'; exit;
});

/*Route::prefix('admin')->restrict('auth:authorize', function() {
	Route::add('GET', '/contas', function() {
		echo 'rotas com prefixos';
	});
});*/

Route::group(['auth' => 'authorize'], function() {
	Route::add('GET', '/usuarios', function() {
		echo 'users'; exit;
	});
});


/*
$routes = new Route();

$routes->addRoute('/', [AppController::class, 'index']);
$routes->addRoute('/user/[0-9]+', [AppController::class, 'update']);

$routes->addRoute('/templates', [Templates::class, 'load_templates']);
$routes->addRoute('/api/template', [Templates::class, 'upload_template']);

$routes->addRoute('/editor/[0-9]+', [Templates::class, 'open_editor']);

//$routes->addRoute('/editor/[0-9]+', [Editor::class, 'open_template']);

$routes->addRoute('/web_builder', [Editor::class, 'web_builder']);

$routes->dispatch();
*/