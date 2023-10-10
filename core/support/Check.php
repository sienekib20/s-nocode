<?php

namespace core\support;

use core\classes\Response;

class Check
{
    public static function despatchClassIfExists($class)
    {
        if (!class_exists($class)) {

            response()->setHttpResponseCode(404);

            throw new \Exception("This controller `{$class}` not exists", 1);
        }

        return (new $class());
    }

    public static function viewContainDot($view)
    {
        return str_contains($view, '.');
    }

    public static function fileExist($file)
    {
        if (!file_exists($file)) {

            response()->setHttpResponseCode(404);

            throw new \Exception("This File `{$file}` not exists", 1);
        }

        return $file;
    }
}
