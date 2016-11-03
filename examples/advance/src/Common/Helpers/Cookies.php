<?php

namespace Common\Helpers;

class Cookies
{
    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public static function set($name, $value)
    {
        return setcookie($name, $value, time() + 3600 * 24 * 30 * 3, "/", $_SERVER['HTTP_HOST']);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public static function delete($name)
    {
        return setcookie($name, '', time() - 3600, "/", $_SERVER['HTTP_HOST']);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public static function get($name)
    {
        return !empty($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }
}