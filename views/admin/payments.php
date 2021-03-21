<?php

use App\controllers\OrderController;

include_once "common/authheader.php";
$title = "Payment History";
$currentnav = "payments";
$oc = new OrderController();
$payments = $oc->getAllPayments();

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
                        <h4 class="card-title">PAYMENTS</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th>ORDER ID</th>
                                    <th>AMOUNT</th>
                                    <th>DELIVERY&nbsp;FEE</th>
                                    <th>BALANCE <br /><small class="text-muted">(Amount to be paid to client)</small></th>
                                    <th>STATUS</th>
                                    <th>UPDATED&nbsp;AT</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($payments as $payment) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label="ORDER ID">
                                            #<?= $payment["id"] ?>
                                        </td>
                                        <td role="cell" data-label="AMOUNT : "><?= number_format($payment["totalamount"], 2) ?></td>
                                        <td role="cell" data-label="DELIVERY FEE : "><?= number_format($payment["delivery_fee"], 2) ?></td>
                                        <td role="cell" data-label="BALANCE : ">
                                            <?= number_format(($payment["totalamount"] - $payment["delivery_fee"]), 2) ?>
                                        </td>
                                        <td class="text-uppercase" role="cell" data-label="PAYMENT STATUS : ">
                                            <span class="badge badge-<?= determineClass($payment["payment_status"]) ?> p-2"><?= $payment["payment_status"] ?></span>
                                        </td>
                                        <td role="cell" data-label="UPDATED AT : ">
                                            <?= empty($payment["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($payment["updated_at"])) ?>
                                        </td>
                                        <td>
                                            <?php if ($payment["payment_status"] === "paid") { ?>
                                                <button type="button" class="btn btn-transparent btn-sm mx-1" id="verifybtn" data-orderid="<?= $payment["id"] ?>">
                                                    <img src="/assets/icons/money.svg" width="15px" height="15px" /> Verify Payment
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-transparent btn-sm mx-1" disabled>
                                                    <img src="/assets/icons/money.svg" width="15px" height="15px" /> Verify Payment
                                                </button>
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
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>

    <!-- <script src="/assets/js/client/orders.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true,
            "order": [
                [5, "desc"]
            ]
        });
    </script>
</body>

</html>