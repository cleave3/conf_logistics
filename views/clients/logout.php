<?php

use App\utils\Session;

Session::destroy();
if (!isset($auth)) {
    header("location:/clients/login");
    exit;
}
