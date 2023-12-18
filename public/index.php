<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Iniciar as sessions

session_start();

/*
|--------------------------------------------
| Auto carregamento das classes
|--------------------------------------------
*/
require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------
| Responsável por todas as funcoes automáticas
|--------------------------------------------
*/
require __DIR__ . '/../src/Support/helpers.php';


// Carregando variáveis de ambiente

$env = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$env->load();


// Startando a aplicação

app()->start();
