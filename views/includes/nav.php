<?php
session_start();
$activenav = "nav-item active";
$nav = "nav-item";

$dashboardlink = "#";
if (isset($_SESSION["clientid"])) $dashboardlink = "/clients/dashboard";
if (isset($_SESSION["userid"])) $dashboardlink = "/admin/dashboard";
if (isset($_SESSION["agentid"])) $dashboardlink = "/agents/dashboard";

$auth = isset($_SESSION["clientid"]) || isset($_SESSION["userid"]) || isset($_SESSION["agentid"]);
?>
<div class="header-top-w3layouts">
    <div class="container">

        <header>
            <div class="top-head-w3-agile text-left">
                <div class="row top-content-info-wthree">
                    <div class="col-lg-7 top-content-right">
                        <div class="row">
                            <div class="col-md-6 callnumber text-left">
                                <h6>Call Us : <span> <a href="tel:09029069437">09029069437</a>, <a href="tel:09063727152">09063727152</a> , <a href="tel:08148971890">08148971890</a></span></h6>
                            </div>
                            <div class="col-md-6 top-social-icons p-0">
                                <ul class="social-icons d-flex justify-content-end">
                                    <li class="mr-1"><a href="#"><span class="fab fa-facebook-f"></span></a></li>
                                    <li class="mx-1"><a href="#"><span class="fab fa-twitter"></span></a></li>
                                    <li class="mx-1"><a href="#"><span class="fab fa-google-plus-g"></span></a></li>
                                    <li class="mx-1"><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="logo text-left">
                    <h1>
                        <a class="navbar-brand" href="/home">
                            Confidebat</a>
                    </h1>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">

                    </span>

                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-lg-auto text-right">
                        <li class="<?= $title == "Home" ? $activenav : $nav ?>">
                            <a class="nav-link" href="/">Home
                            </a>
                        </li>
                        <?php if (!$auth) { ?>
                            <li class="<?= $title == "Login" ? $activenav : $nav ?>">
                                <a class="nav-link" href="/login">Sign In</a>
                            </li>
                        <?php } ?>

                    </ul>
                    <div class="log-in">
                        <?php if (!$auth) { ?>
                            <a class="btn text-uppercase" href="/clients/register">
                                Create Account</a>
                        <?php } else { ?>
                            <a class="btn text-uppercase" href="<?= $dashboardlink ?>">
                                Dashboard <i class="fa fa-user" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                </div>

            </nav>
        </header>
    </div>
</div>