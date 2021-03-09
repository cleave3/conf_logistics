<?php

use App\utils\Session;

Session::start();
$auth = Session::get("auth");
$companyname = Session::get("companyname");
$name = Session::get("username");
$image = Session::get("image");
$image = !$image ? "default.jpg" : $image;
$emailverified = Session::get("emailverified");
$profileverified = Session::get("profileverified");

if (!$auth) {
    header("location:login");
    exit;
}
