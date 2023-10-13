<?php

use app\controllers\AppController;
use app\controllers\Templates;
use core\classes\Route;

$routes = new Route();

$routes->addRoute('/', [AppController::class, 'index']);
$routes->addRoute('/user/[0-9]+', [AppController::class, 'update']);

$routes->addRoute('/templates', [Templates::class, 'load_templates']);
$routes->addRoute('/api/template', [Templates::class, 'upload_template']);


$routes->dispatch();
