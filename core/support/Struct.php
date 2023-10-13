<?php

namespace core\support;

use DateTime;
use Exception;

class Struct
{
    private static $config_dir = __DIR__ . '/../config';

    private static $folder_structure = [
        'app/assets/css',
        'app/assets/js',
        'app/assets/img',
        'app/controllers',
        'app/models',
        'views/pages'
    ];

    private static function put_contain($basefile, $novo_destino)
    {

        $base = get_file($basefile);

        if ($base != null) {
            return copy($base, $novo_destino);
        }
    }

    public static function put_file(string $dir, int $index, array $file, string $type)
    {
        $countError = 1;

        array_shift($file);

        foreach ($file as $ficheiro) {
            for ($i = 0; $i < count($f = $ficheiro['type']); $i++) {

                $part = explode('/', $f[$i])[0];

                $type = ($part == 'text') ? $f[$i] : $part;

                $dir_part = explode('/', rtrim($dir, '/'));
                $dir_part = end($dir_part);

                if ($dir_part == 'css' && $type == 'text/css') {

                    upload($ficheiro['tmp_name'][$i], $dir . $ficheiro['name'][$i]);
                } else
                if ($dir_part == 'js' && $type == 'text/javascript') {

                    upload($ficheiro['tmp_name'][$i], $dir . $ficheiro['name'][$i]);
                } else
                if ($dir_part == 'img' && $type == 'image') {

                    upload($ficheiro['tmp_name'][$i], $dir . $ficheiro['name'][$i]);
                } else
                if ($dir_part == 'pages' && $type == 'text/html') {

                    if ($ficheiro['name'][$i] == 'index.html') {
                        $dir = str_replace('pages', '', rtrim($dir, '/'));
                        upload($ficheiro['tmp_name'][$i], $dir . $ficheiro['name'][$i]);
                    } else {

                        upload($ficheiro['tmp_name'][$i], $dir . $ficheiro['name'][$i]);
                    }
                }
            }
        }
    }


    public static function create_structure($template_name)
    {

        $created_dir = [];

        $reference = str_replace(' ', '', $template_name);
        $template_directory =  date('d') . '_' . date('m') . '_' . date('Y') . "_nocode_{$reference}_template";

        $root = self::create_dir($template_directory);
        $full_path = $root;
        $created_dir['root'] = $root;
        $created_dir['reference'] = $template_directory;


        if ($root) {
            self::put_contain('index', $full_path . 'index.php');
            self::put_contain('htaccess', $full_path . '.htaccess');

            foreach (self::$folder_structure as $folder) {

                $root = explode('/', rtrim($root, '/'));
                $root = end($root) . '/';

                $created_dir[$folder] = self::create_dir($root . $folder);

                //self::put_file($created_dir[$folder], 0, $files, '');

                $toSend = explode('/', $folder);
                $toSend = end($toSend);

                if ($folder == 'app/controllers' || $folder == 'app/models') {

                    $toSend = ($folder == 'app/controllers') ? 'Pagecontroller' : 'Pagemodel';

                    self::put_contain($toSend, $created_dir[$folder] . $toSend . '.php');
                }
            }

            //return rtrim($root, '/');
            return $created_dir;
        }
        return null;
    }

    public static function create_dir($dirname)
    {
        try {
            $directory = storage('templates/' . $dirname);

            if (!file_exists($directory)) {

                if (mkdir($directory, 0777, true)) {

                    response()->setHttpResponseCode(200);

                    return $directory;
                }

                throw new Exception("Erro ao cria a pasta {$directory}, tente mais tarde!", 1);
            }

            return null;
        } catch (Exception $ex) {

            repport('Erro de criação de pasta', $ex, 500);
        }
    }
}
