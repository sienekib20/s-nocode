<?php

// Inicializar as sessões

session_start();

// Gerenciador de carregamento de classes

require __DIR__ . '/../vendor/autoload.php';

// Carrega as variáveis de ambiente

$env = Dotenv\Dotenv::createImmutable(abs_path());
$env->load();

// Inicialização do aplicativo

app()->run();