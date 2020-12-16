<?php

use App\utils\Response;

require __DIR__ . '/../vendor/autoload.php';

$request = $_SERVER["REQUEST_URI"];

$path_to_file = __DIR__ . $request . ".php";

if (file_exists($path_to_file)) {
    require $path_to_file;
} else {
    $response = new Response();
    echo $response->json(["status" => 404, "message" => "route not found"]);
}
