<?php

use app\controllers\AppController;
use core\classes\Route;

$routes = new Route();

$routes->addRoute('/', [AppController::class, 'index']);
$routes->addRoute('/user/[0-9]+', [AppController::class, 'update']);

$routes->dispatch();