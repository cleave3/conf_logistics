<?php

use App\controllers\PackageController;

include_once "common/authheader.php";
$title = "Waybills";
$currentnav = "waybills";
$pc = new PackageController();
$packages = $pc->getAllWayBills();
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
                        <h4 class="card-title">WAYBILLS</h4>
                    </div>
                    <div class="card-body">
                        <div class="responsivetable table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>SELLER</th>
                                        <th>SELLER&nbsp;TELEPHONE</th>
                                        <th>TITLE</th>
                                        <th>WEIGHT</th>
                                        <th>TRANSPORT&nbsp;COMPANY</th>
                                        <th>DRIVER&nbsp;TELEPHONE</th>
                                        <th>DESTINATION</th>
                                        <th>STATUS</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    foreach ($packages as $package) {
                                    ?>
                                        <tr>
                                            <td data-label="ID : ">#<?= $package["id"] ?></td>
                                            <td data-label="SELLER">
                                                <?= $package["companyname"] ?>
                                            </td>
                                            <td data-label="SELLER TELEPHONE">
                                                <?= $package["telephone"] ?>
                                            </td>
                                            <td data-label="TITLE"><?= $package["package_title"] ?></td>
                                            <td data-label="WEIGHT : "><?= $package["weight"] ?>KG</td>
                                            <td data-label="TRANSPORT COMPANY : "><?= $package["transport_company"] ?></td>
                                            <td data-label="DRIVER TELEPHONE : "><?= $package["driver_number"] ?></td>
                                            <td data-label="DESTINATION : "><?= $package["location"] ?></td>
                                            <td class="font-weight-bold text-uppercase">
                                                <span class="badge badge-<?= determineClass($package["status"]) ?> p-2"><?= $package["status"] ?></span>
                                            </td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <a class="btn btn-sm mx-1 btn-secondary" href="/admin/waybills/detail?packageid=<?= $package["id"] ?>" title="Package Details">
                                                    <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                                </a>
                                                <?php if (in_array($package["status"], ["sent"])) { ?>
                                                    <a class="btn btn-sm mx-1 btn-warning text-dark" href="/admin/waybills/process?packageid=<?= $package["id"] ?>" title="Process waybill">
                                                        Process
                                                    </a>
                                                <?php } ?>
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
            fixedHeader: true,
        });
    </script>
</body>

</html>