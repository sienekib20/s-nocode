<?php
// Container.php
namespace Sienekib\Mehael\Support;

class Container
{
    protected static $services = [];

    public static function bind($name, $callback)
    {
        static::$services[$name] = $callback;
    }

    public static function resolve($name)
    {
        if (isset(static::$services[$name])) {
            $callback = static::$services[$name];
            return $callback();
        } else {
            throw new \Exception("Service '$name' not found.");
        }
    }

    public static function loadServices($directory)
    {
        $files = glob($directory . '/*.php');
        foreach ($files as $file) {
            require_once $file;
        }
    }
}
