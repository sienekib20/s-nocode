<?php

namespace core\templates;

class View
{
    public static function make($view, $params = [])
    {
        $base_container = self::get_base_container();

        $view_content = self::get_view_content($view, $params);

        echo str_replace('{{content}}', $view_content, $base_container);
        exit;
    }

    private static function get_base_container()
    {
        ob_start();

        include view_path() . 'main.html';

        return ob_get_clean();
    }

    private static function get_view_content($view, $params = [])
    {
        $path = view_path();

        if (str_contains($view, '.')) {

            $views = explode('.', $view);

            foreach ($views as $view) {
                if (is_dir($path . $view)) {
                    $path = $path . $view . '/';
                }
            }
            $view = $path . end($views) . '.html';
        } else {
            $view = $path . $view . '.html';
        }

        foreach ($params as $param => $value) {
            $$param = $value;
        }

        if (!file_exists($view)) {
            die("Tela {{$view}} não encontrada");
        } else {

            ob_start();

            include $view;

            return ob_get_clean();
        }
    }
}
