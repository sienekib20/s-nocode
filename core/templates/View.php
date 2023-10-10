<?php

namespace core\templates;

use core\support\Check;

class View
{
    private static function mainContainer($fileView)
    {
        $containerView = file_get_contents(view_path() . 'main.html');

        var_dump($fileView);
        exit;

        $containerView = str_replace('{{content}}', $fileView, $containerView);

        echo $containerView;
    }

    public static function render($view, $params = [])
    {
        $path = view_path();

        $renderedView = '';

        try {
            if (Check::viewContainDot($view)) {

                $views = explode('.', $view);
                foreach ($views as $fileView) {

                    if (is_dir($path . $fileView)) {

                        $path .= $fileView . '/';
                    } else {

                        $renderedView = $path . $fileView . '.html';
                    }
                }
            } else {

                $renderedView = $view . '.html';
            }

            foreach ($params as $key => $value) {
                $$key = $value;
            }

            if (!file_exists($renderedView)) {

                throw new \Exception("Screen `{$renderedView}` Not Found", 1);
            }

            require $renderedView;

            /*ob_start();

            include $renderedView;

            $f = ob_get_clean();
            self::mainContainer($f);*/
        } catch (\Exception $ex) {

            response()->setHttpResponseCode(404);

            die('Error: ' . $ex->getMessage());
        }
    }
}
