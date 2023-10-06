<?php

use core\classes\database\Database;
use core\classes\Request;
use core\classes\Response;
use core\templates\View;

if (!function_exists('root')) :

    function root()
    {
        return dirname(__DIR__, 2) . '/';
    }

endif;

if (!function_exists('response')) :

    function response()
    {
        static $instance = null;

        if (is_null($instance)) {

            $instance = (new Response());
        }

        return $instance;
    }

endif;

if (!function_exists('view_path')) :

    function view_path()
    {
        return root() . 'views/';
    }

endif;

if (!function_exists('view')) :

    function view($view, $params = [])
    {
        return View::render($view, $params);
    }

endif;


if (!function_exists('parts')) :

    function parts($part)
    {
        $part = str_replace('.', '/', $part);

        try {

            if (file_exists(view_path() . "parts/{$part}.html")) {

                include view_path() . "parts/{$part}.html";

                return;
            }

            throw new Exception("Não foi encontrado a tela {$part}", 1);
        } catch (\Exception $ex) {
            die('Erro: ' . $ex->getMessage());
        }
    }

endif;


if (!function_exists('database')) :

    function database($table)
    {
        return (new Database($table));
    }

endif;


if (!function_exists('request')) :

    function request()
    {
        static $instance = null;

        if ($instance == null) {
            $instance = (object) new Request();
        }

        return $instance;
    }

endif;
