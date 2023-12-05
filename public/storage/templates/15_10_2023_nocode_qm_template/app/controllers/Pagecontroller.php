<?php

class PageController
{
    public function index()
    {
        require_once __DIR__ . '/../models/PageModel.php';
        $pageModel = new PageModel();

        $pageTitle = 'Página Inicial';
        require __DIR__ .'/../../views/index.html';
        exit;
    }
}
