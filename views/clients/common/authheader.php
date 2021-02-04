<?php

use App\utils\Session;

Session::start();
$auth = Session::get("auth");
$companyname = Session::get("companyname");
$name = Session::get("username");
$emailverified = Session::get("emailverified");
$profileverified = Session::get("profileverified");

if (!isset($auth)) {
    header("location:login");
    exit;
}
