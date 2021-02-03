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
        foreach ($var as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public static function get($key)
    {
        if (!isset($_SESSION[$key])) throw new \Exception("Authenticated access or session timeout (Reload and Try again)");
        return $_SESSION[$key];
    }

    public static function destroy()
    {
        session_start();
        session_destroy();
    }
}
