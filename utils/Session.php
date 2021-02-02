<?php

namespace App\utils;

class Session
{

    public static function start()
    {
        session_start();
    }

    public static function set(array $var)
    {
        $vlaue = "";
        foreach ($var as $key => $value) {
            $_SESSION[$key] = $value;
            $vlaue .= $key . " " . $value;
        }

        return $vlaue;
    }

    public static function get($key)
    {
        return $_SESSION[$key];
    }

    public static function destroy()
    {
        session_start();
        session_destroy();
    }
}
