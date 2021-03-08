<?php

use App\utils\Session;

Session::start();
$adminid = Session::get("adminid");
$userid = Session::get("userid");
$companyname = Session::get("companyname");
$name = Session::get("username");
$emailverified = Session::get("emailverified");
$profileverified = Session::get("profileverified");

if (!$adminid || !$userid) {
    header("location:login");
    exit;
}
