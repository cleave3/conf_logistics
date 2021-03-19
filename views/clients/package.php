<?php

use App\controllers\PackageController;

include_once "common/authheader.php";
$title = "Client Package";
$currentnav = "package";
$pc = new PackageController();
$packages = $pc->getPackagesByClientId()["data"];
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>

            <div class="content">
                <a href="/clients/package/add" class="btn btn-sm btn-success">Send New <i class="fa fa-book"></i></a>
                <div class="card">
                    <marquee class="text-warning">Please donot send package on transit. We advice that all package are sent with a registered company</marquee>
                    <div class="card-header">
                        <h4 class="card-title">PACKAGES</h4>
                    </div>
                    <div class="card-body">
                        <div class="responsivetable table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th></th>
                                        <th>TITLE</th>
                                        <th>WEIGHT</th>
                                        <th>TRANSPORT&nbsp;COMPANY</th>
                                        <th>DRIVER&nbsp;NUMBER</th>
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
                                            <td data-label="SN : "><?= $sn ?></td>
                                            <td data-label="">
                                                <img class="img-fluid" src="/files/document/<?= $package["image"] ?? "camera.svg" ?>" alt="..." style="width: 40px; height: 40px; cursor:pointer;">
                                            </td>
                                            <td data-label="TITLE"><?= $package["package_title"] ?></td>
                                            <td data-label="WEIGHT : "><?= $package["weight"] ?>KG</td>
                                            <td data-label="TRANSPORT COMPANY : "><?= $package["transport_company"] ?></td>
                                            <td data-label="DRIVER NUMBER : "><?= $package["driver_number"] ?></td>
                                            <td data-label="DESTINATION : "><?= $package["destination"] ?></td>
                                            <td class="font-weight-bold text-uppercase">
                                                <span class="badge badge-<?= determineClass($package["status"]) ?> p-2"><?= $package["status"] ?></span>
                                            </td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <?php if (in_array($package["status"], ["onhand", "pending"])) { ?>
                                                    <a class="btn btn-sm mx-1 btn-success" href="#" title="Send Item Now" onclick='sendPackageNow(<?= $package["id"] ?>)'>
                                                        <img src="/assets/icons/send.svg" width="15px" height="15px" />
                                                    </a>
                                                <?php } ?>
                                                <a class="btn btn-sm mx-1 btn-secondary" href="/clients/package/details?packageid=<?= $package["id"] ?>" title="Package Details">
                                                    <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                                </a>
                                                <a class="btn btn-sm mx-1 btn-primary" href="/clients/package/edit?packageid=<?= $package["id"] ?>" title="Edit package">
                                                    <img src="/assets/icons/edit.svg" width="15px" height="15px" />
                                                </a>
                                                <!-- <a class="btn btn-sm mx-1 btn-danger" href="" title="Delete Package">
                                                    <img src="/assets/icons/trash.svg" width="15px" height="15px" />
                                                </a> -->
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
    <script src="/assets/js/client/package.js"></script>
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>