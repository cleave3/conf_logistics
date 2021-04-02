<?php

use App\controllers\WaybillController;

include_once "common/authheader.php";
$title = "Client Waybill Request";
$currentnav = "waybill";
$wc = new WaybillController();
$waybills = $wc->waybills();
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
                        <h4 class="card-title">WAYBILL REQUEST</h4>
                    </div>
                    <div class="card-body">
                        <div class="responsivetable table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>COMPANY</th>
                                        <th>TELEPHONE</th>
                                        <th>WAYBILL&nbsp;FEE (NGN)</th>
                                        <th>PAYMENT&nbsp;SOURCE</th>
                                        <th>DESTINATION</th>
                                        <th>STATUS</th>
                                        <th>TRANSPORT&nbsp;COMPANY</th>
                                        <th>DRIVER&nbsp;NUMBER</th>
                                        <th>CREATED&nbsp;AT</th>
                                        <th>UPDATED&nbsp;AT</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    foreach ($waybills as $waybill) {
                                    ?>
                                        <tr>
                                            <td data-label="ID : ">#<?= $waybill["id"] ?></td>
                                            <td data-label="COMPANY : "><?= $waybill["companyname"] ?></td>
                                            <td data-label="TELEPHONE : "><?= $waybill["telephone"] ?></td>
                                            <td data-label="WAYBILL FEE (NGN) : "><?= number_format($waybill["fee"]) ?></td>
                                            <td data-label="PAYMENT SOURCE : "><?= $waybill["payment_source"] ?></td>
                                            <td data-label="DESTINATION : "><?= $waybill["destination"] ?></td>
                                            <td data-label="STATUS : " class="font-weight-bold text-uppercase">
                                                <span class="badge badge-<?= determineClass($waybill["status"]) ?> p-2"><?= $waybill["status"] ?></span>
                                            </td>
                                            <td data-label="TRANSPORT COMPANY : "><?= empty($waybill["transport_company"]) ? "Not yet sent"  : $waybill["transport_company"] ?></td>
                                            <td data-label="DRIVER NUMBER : "><?= empty($waybill["driver_number"]) ? "Not yet sent" : $waybill["driver_number"] ?></td>
                                            <td role="cell" data-label="CREATED AT : ">
                                                <?= date("Y-m-d, H:m:s a", strtotime($waybill["created_at"])) ?>
                                            </td>
                                            <td role="cell" data-label="UPDATED AT : ">
                                                <?= empty($waybill["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($waybill["updated_at"])) ?>
                                            </td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <a class="btn btn-sm mx-1 btn-secondary" href="/admin/waybillrequest/details?id=<?= $waybill["id"] ?>" title="waybill Details">
                                                    <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                                </a>
                                                <?php if (in_array($waybill["status"], ["pending"])) { ?>
                                                    <a class="btn mx-1 btn-warning text-dark" href="/admin/waybillrequest/process?id=<?= $waybill["id"] ?>" title="Process request">
                                                        process
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
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true,
            "order": [
                [0, "desc"]
            ]
        });
    </script>
</body>

</html>