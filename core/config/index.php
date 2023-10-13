<?php

$route = isset($_GET['route']) ? $_GET['route'] : 'home';

switch ($route) {
    case 'home':
        require_once 'app/controllers/PageController.php';
        $pageController = new PageController();
        $pageController->index();
        break;
    default:
        echo 'Página não encontrada';
        break;
}
