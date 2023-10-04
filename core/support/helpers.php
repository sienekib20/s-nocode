<?php

use core\classes\database\Database;
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

if (!function_exists('database')) :

    function database($table)
    {
        return (new Database($table));
    }

endif;