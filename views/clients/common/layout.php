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
$title = "Change Password";
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                content here
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
</body>

</html>