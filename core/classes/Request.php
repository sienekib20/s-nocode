<?php

namespace core\classes;

class Request
{
    private $attributes;

    public function __construct($key = '')
    {
        $this->attributes = (object) $_REQUEST;

        return $this->attributes->$key ?? null;
    }

    public function all()
    {
        return (array) $this->attributes;
    }

    public function file()
    {
        return $_FILES;
    }

    public function path()
    {
        $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $current_path = trim($current_path, '/');

        return $current_path;
    }

    public function methodIs($method)
    {
        return strtolower($_SERVER['REQUEST_METHOD']) == strtolower($method);
    }

    public function __set($key, $value)
    {
        $this->attributes->$key = $value;
    }

    public function __get($key)
    {
        return $this->attributes->$key ?? null;
    }
}
