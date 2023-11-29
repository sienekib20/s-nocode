<?php

use app\controllers\AppController;
use app\controllers\Editor;
use app\controllers\Templates;
use core\classes\Route;

$routes = new Route();

$routes->addRoute('/', [AppController::class, 'index']);
$routes->addRoute('/user/[0-9]+', [AppController::class, 'update']);

$routes->addRoute('/templates', [Templates::class, 'load_templates']);
$routes->addRoute('/api/template', [Templates::class, 'upload_template']);

$routes->addRoute('/editor/[0-9]+', [Templates::class, 'open_editor']);

//$routes->addRoute('/editor/[0-9]+', [Editor::class, 'open_template']);

$routes->addRoute('/web_builder', [Editor::class, 'web_builder']);

$routes->dispatch();
