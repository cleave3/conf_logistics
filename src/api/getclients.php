<?php

namespace App\api;

use App\controllers\UserController as UserController;

$user = new UserController();

echo $user->index();
