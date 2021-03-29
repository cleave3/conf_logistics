<?php

use App\utils\Session;

Session::start();
$adminid = Session::get("adminid");
$userid = Session::get("userid");
$companyname = Session::get("companyname");
$name = Session::get("username");
$image = Session::get("image");
$image = !$image ? "default.jpg" : $image;
$emailverified = Session::get("emailverified");
$profileverified = Session::get("profileverified");

if (!$adminid && !$userid) {
    header("location:/admin/login");
    exit;
}
