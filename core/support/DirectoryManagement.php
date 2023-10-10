<?php

namespace core\support;

use Exception;

class DirectoryManagement
{
    private static array $subDir = [
        'assets/css',
        'assets/js',
        'assets/img',
        'pages'
    ];

    public static function createFolderStructure($templateName)
    {
        try {

            $createdDirectory = [];

            $templateName =  $templateName;
            $templateName =  str_replace('-', '', date('d-m-Y')) . 'sp_' . $templateName . '_template';

            $templateDirectory = self::createDir($templateName);

            if ($templateDirectory) {

                foreach (self::$subDir as $folder) {

                    $root = explode('/', rtrim($templateDirectory, '/'));
                    $root = end($root) . '/';

                    $createdDirectory[$folder] = self::createDir($root . $folder);
                }

                $createdDirectory['temp_dir'] = $templateDirectory;
                $createdDirectory['temp_name'] = $templateName;

                return $createdDirectory;
            }

            //throw new Exception("Já existe um diretório com o mesmo nome {$templateName}", 1);
        } catch (Exception $ex) {
            repport('Erro de Diretório', $ex, 500);
        }
    }

    public static function mu_file(array $file, int $index, array $structure, string $type): int
    {
        $countError = 1;
        try {

            if ($type == 'text/css') {

                move_uploaded_file($file['tmp_name'][$index], $structure['assets/css'] . $file['name'][$index]);
                $countError = 0;
            } else
            if ($type == 'text/javascript') {

                move_uploaded_file($file['tmp_name'][$index], $structure['assets/js'] . $file['name'][$index]);
                $countError = 0;
            } else
            if (explode('/', $type)[0] == 'image') {

                move_uploaded_file($file['tmp_name'][$index], $structure['assets/img'] . $file['name'][$index]);
                $countError = 0;
            } else {
                throw new Exception("Erro ao fazer o upload do ficheiro {$file['name'][$index]}", 1);
            }
        } catch (Exception $ex) {
            repport('Erro de upload', $ex, 500);
        }

        return $countError;
    }

    private static function createDir($dirname)
    {
        try {
            $directory = storage('templates/default/' . $dirname);

            if (!file_exists($directory)) {

                if (mkdir($directory, 0777, true)) {

                    response()->setHttpResponseCode(200);

                    return $directory;
                }

                throw new Exception("Error on create directory {$directory}, try later!", 1);
            }

            return null;
        } catch (Exception $ex) {

            repport('Create Dir Error', $ex, 500);
        }
    }
}
