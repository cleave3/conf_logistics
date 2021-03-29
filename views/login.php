<?php
$title = "Login";
require("includes/header.php");

?>


<body style="background: linear-gradient(#c6e1e3b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <?php require("includes/nav.php") ?>
    <div class="d-flex justify-content-center align-items-center my-5" style="height: 100vh;">
        <div class="d-flex justify-content-around flex-wrap w-75">
            <div>
                <img src="/assets/icons/delivery-man.svg" class="img-fluid">
                <div class="d-flex justify-content-center m-2">
                    <a class="btn btn-info w-100 btn-sm" href="/agents/login">Agent Login <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
                </div>
            </div>
            <div>
                <img src="/assets/icons/customer.svg" class="img-fluid">
                <div class="d-flex justify-content-center m-2">
                    <a class="btn btn-info w-100 btn-sm" href="/clients/login">Client Login <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php require("includes/footer.php") ?>