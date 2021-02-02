<?php

namespace App\middleware;

use App\config\DotEnv;
use Firebase\JWT\JWT;


(new DotEnv(__DIR__ . '/../.env'))->load();

class Auth
{
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
