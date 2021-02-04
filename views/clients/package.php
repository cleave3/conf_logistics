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
                <a href="/clients/package/add" class="btn btn-sm btn-success">New Package <i class="fa fa-book"></i></a>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">PACKAGES</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>TITLE</th>
                                        <th>WEIGHT</th>
                                        <th>LOCATION</th>
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
                                            <td><?= $sn ?></td>
                                            <td><?= $packages["package_title"] ?></td>
                                            <td><?= $packages["weight"] ?></td>
                                            <td><?= $packages["destination"] ?></td>
                                            <td><?= $packages["status"] ?></td>
                                            <td class="d-flex">
                                                <a class="btn btn-sm btn-primary" href="/clients/package/edit?<?= $package["id"] ?>" title="Edit package"><i class="fa fa-pencil text-white" aria-hidden="true"></i></a>
                                                <a class="btn btn-sm btn-outline-danger" href=""><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>
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
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>