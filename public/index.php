<?php


/*header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");*/

ini_set('session.gc_lifetime', 0);
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', '1');
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // 1800 segundos (30 minutos) de inatividade, a sessão expirou
    session_unset();     // limpa as variáveis de sessão
    session_destroy();   // destrói a sessão
}

$_SESSION['LAST_ACTIVITY'] = time(); // atualiza o último momento de atividade

// Restante do seu código aqui



//echo phpinfo();

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

$env = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

// Iniciar as sessions

Sienekib\Mehael\Support\Session::start();


// Startando a aplicação
app()->start();
