<?php

namespace App\middleware;

use Firebase\JWT\JWT;

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
}
