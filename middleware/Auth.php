<?php

namespace App\middleware;

use App\config\DotEnv;
use App\utils\Session;
use Firebase\JWT\JWT;

(new DotEnv(__DIR__ . '/../.env'))->load();

class Auth
{
    public static function checkAuth($key)
    {
        $auth = Session::get($key);
        if (!$auth) throw new \Exception("session timeout, Kindly reload, login and try again");
    }

    public static function checkRole(array $roles)
    {
        $role = Session::get("role");
        if (!in_array($role, $roles)) throw new \Exception("Unathorized Access");
    }

    public static function checkPermissions(array $permissions)
    {
        Session::start();
        $auth = Session::get("auth");
        if (!$auth) throw new \Exception("session timeout, Kindly reload, login and try again");
        $permission = self::decodeToken($auth)["permissions"];
        if (!in_array($permission, $permissions)) throw new \Exception("Unathorized Access");
    }

    public static function genToken($payload)
    {
        return JWT::encode($payload, getenv("JWT_SECRET"));
    }

    public static function decodeToken($jwt)
    {
        JWT::$leeway = 60;
        $decoded = JWT::decode($jwt, getenv("JWT_SECRET"), array('HS256'));
        return (array)$decoded;
    }

    public static function genHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ["cost" => 10]);
    }

    public static function verifyHash($password, $hash)
    {
        return password_verify($password, $hash);
    }
}