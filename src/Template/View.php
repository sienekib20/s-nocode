<?php

namespace Sienekib\Mehael\Template;

class View
{
    public static function render(string $view, array $params)
    {
        if (!str_contains($view, ':')) {
            echo "View `$view` mal formada";
            exit;
        }
        $path = view_path() . '/';
        $parts = explode(':', $view);

        if (empty($parts[0])) {
            echo "Precisa definir um título para sua tela";
            exit;
        }

        if (str_contains($parts[1], '.')) {
            $views = explode('.', $parts[1]);
            foreach ($views as $view) {
                if (is_dir($path . $view)) {
                    $path = $path . $view . '/';
                }
            }
            $view = $path . end($views) . '.php';
        } else {
            $view = $path . $parts[1] . '.php';
        }

        if (!file_exists($view)) {
            echo "Tela não encontrado";
            response()->setStatusCode(404);
            exit;
        }

        /*foreach ($params as $key => $value) {
      $$key = $value;
    }*/

        ob_start();

        extract($params);

        require $view;

        //$final = ob_get_clean();
        $final = ob_get_contents();

        $final = preg_replace("/<title>(.*)<\/title>/", "<title>$parts[0]</title>", $final);

        //$final = preg_replace('/@Auth::()/', '\Sienekib\Mehael\Support\Auth', $final);



        $final = preg_replace_callback("/@Auth::\s*(.*?)\s*/", function ($matches) {
            return "<?php \Sienekib\Mehael\Support\Auth::{$matches[1]}(); ?>";
        }, $final);


        $final = preg_replace_callback("/@if\((.*?)\)\s*{/", function ($matches) {
            return "<?php if ({$matches[1]}): ?>";
        }, $final);

        $final = self::statements($final, "if", "/@if\((.*?)\)/", "/@endif/", "<?php endif; ?>");
        $final = self::statements($final, "parts", "/@parts\((.*?)\)/");
        $final = self::statements($final, "echo", "/{{(.*?)}}/");

        ob_end_clean();

        echo $final;
        /*eval('?>' . $final);*/

        return;
    }

    private static function statements($archive, string $type, string $pattern, string $find = '', string $replace = '')
    {
        if (!empty($find))
            $archive =  preg_replace($find, $replace, $archive);

        $result = '';
        switch ($type) {
            case 'if':
                $result = preg_replace_callback($pattern, function ($matches) {
                    return "<?php if ({$matches[1]}): ?>";
                }, $archive);
                break;
            case 'parts':
                $result = preg_replace_callback($pattern, function ($matches) {
                    return "<?php parts({$matches[1]}) ?>";
                }, $archive);
                break;
            case 'echo':
                $result = preg_replace_callback($pattern, function ($matches) {
                    return "<?php echo {$matches[1]} ?>";
                }, $archive);
                break;
        }
        return $result;
    }
}
