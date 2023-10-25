<?php

namespace core\support;

class Session
{
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return self::has($key) ? $_SESSION[$key] : null;
    }

    private static function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function all()
    {
        return $_SESSION;
    }

    public static function reset()
    {
        if (!empty($_SESSION)) {

            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }
        }
    }
}
