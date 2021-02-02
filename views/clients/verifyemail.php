<?php

use App\controllers\ClientController;

$title = "Verify Email";
if (isset($_GET["token"])) {
    $cc = new ClientController();
    $verify = $cc->verifyEmail();
}
include_once "common/header.php";
?>
<div style="background: linear-gradient(#000000b3, #000000b3), url(../assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="wrap ">
        <?php if (isset($verify) && $verify["status"]) { ?>
            <div class="animation-ctn">
                <div class="icon icon--order-success svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="154px" height="154px">
                        <g fill="none" stroke="#22AE73" stroke-width="2">
                            <circle cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                            <circle id="colored" fill="#22AE73" cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                            <polyline class="st0" stroke="#fff" stroke-width="10" points="43.5,77.8 63.7,97.9 112.2,49.4 " style="stroke-dasharray:100px, 100px; stroke-dashoffset: 200px;" />
                        </g>
                    </svg>
                </div>
                <div class="bg-light p-2">
                    <h3><?= $verify["message"] ?></h3>
                    <a class='btn proceed' href="/client/login">Proceed to Login </a>
                </div>
            </div>
        <?php } else { ?>
            <div class="animation-ctn">
                <div class="icon icon--order-success svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="154px" height="154px">
                        <g fill="none" stroke="#F44812" stroke-width="2">
                            <circle cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                            <circle id="colored" fill="#F44812" cx="77" cy="77" r="72" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle>
                            <polyline class="st0" stroke="#fff" stroke-width="10" points="43.5,77.8  112.2,77.8 " style="stroke-dasharray:100px, 100px; stroke-dashoffset: 200px;" />
                        </g>
                    </svg>
                </div>
                <div class="bg-light p-2">
                    <h3><?= $verify["message"] ?></h3>
                    <a class='btn proceed' href="/">Back Home</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>