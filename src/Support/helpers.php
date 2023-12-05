<?php

use Mehael\Kernel\Application;

if (!function_exists('abs_path')) :

    // Retorna o caminho absoluto da aplicação
    function abs_path() : string
    {
        return dirname(__DIR__, 2);
    }

endif;

if (!function_exists('app')) :

    // Retorna a instancia da classe Application
    function app() : Application
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Application();
        }
        return $instance;
    }

endif;

if (!function_exists('env')) :

    /* Retorna o valor de ambiente cuja a chave é keu
     e retorna o valor por defeito caso o key não existir
    */
    function env(string $key, string $default = null)
    {   
        
        return $_ENV[$key] ?? $default;
    }
    
endif;