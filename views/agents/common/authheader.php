<?php

use App\utils\Session;

Session::start();
$agentid = Session::get("agentid");
$name = Session::get("agentname");
$image = Session::get("image");
$image = !$image ? "default.jpg" : $image;

if (!$agentid) {
    header("location:login");
    exit;
}
