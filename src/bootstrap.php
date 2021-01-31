<?php

use App\router\Router;
use App\config\DotEnv;

require __DIR__ . '/../vendor/autoload.php';

(new DotEnv(__DIR__ . '/../.env'))->load();

Router::run();
