<?php

use App\controllers\ClientController;

include_once "common/authheader.php";
$title = "Clients";
$currentnav = "clients";
$cc = new ClientController();
$clients = $cc->getallclients();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>

            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">CLIENTS</h4>
                    </div>
                    <div class="card-body">
                        <div class="responsivetable table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>NAME</th>
                                        <th>TELEPHONE</th>
                                        <th>EMAIL</th>
                                        <th>COMPANY&nbsp;NAME</th>
                                        <th>STATE</th>
                                        <th>CITY</th>
                                        <th>STATUS</th>
                                        <th>REGISTRATION&nbsp;DATE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    foreach ($clients as $client) {
                                    ?>
                                        <tr>
                                            <td data-label="">
                                                <img class="img-fluid" src="/files/photo/<?= $client["image"] ?? "camera.svg" ?>" alt="..." style="width: 40px; height: 40px; cursor:pointer;">
                                            </td>
                                            <td data-label="NAME"><?= $client["firstname"] ?> <?= $client["lastname"] ?></td>
                                            <td data-label="TELEPHONE : "><?= $client["telephone"] ?></td>
                                            <td data-label="EMAIL : "><?= $client["email"] ?></td>
                                            <td data-label="COMPANY NAME : "><?= $client["companyname"] ?></td>
                                            <td data-label="STATE : "><?= $client["state"] ?></td>
                                            <td data-label="CITY : "><?= $client["city_town"] ?></td>
                                            <td class="font-weight-bold <?= determineClass($client["status"]) ?>"><?= strtoupper($client["status"]) ?></td>
                                            <td data-label="REG. DATE : "><?= date("Y-m-d H:m:s a", strtotime($client["created_at"])) ?></td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <a class="btn btn-sm mx-1 btn-secondary" href="/admin/clients/details?clientid=<?= $client["id"] ?>" title="Package Details">
                                                    <img src="/assets/icons/history.svg" width="20px" height="20px" />
                                                </a>
                                                <a class="btn btn-sm mx-1 btn-primary" href="/admin/clients/edit?clientid=<?= $client["id"] ?>" title="Edit package">
                                                    <img src="/assets/icons/edit.svg" width="20px" height="20px" />
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                        $sn++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
    <!-- <script src="/assets/js/client/package.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>