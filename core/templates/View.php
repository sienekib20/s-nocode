<?php

namespace core\templates;

class View
{
    private static function getBaseContent()
    {
        ob_start();

        include view_path() . 'main.html';

        return ob_get_clean();
    }

    private static function getViewContent($view, $params)
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

        if (file_exists($view)) {

            ob_start();

            include $view;

            return ob_get_clean();
        }

        die('Tela não encontrada');
    }

    public static function render($view, $params = [])
    {
        $base = self::getBaseContent();

        $viewContent = self::getViewContent($view, params: $params);

        echo str_replace('{{content}}', $viewContent, $base);
    }
}
