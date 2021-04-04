<?php

use App\controllers\TransactionController;

include_once "common/authheader.php";
$title = "Payment History";
$currentnav = "payments";

$tc = new TransactionController();
$payments = $tc->getClienPayments();

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
                                    <th>S/N</th>
                                    <th>REFERENCE</th>
                                    <th>AMOUNT</th>
                                    <th>STATUS</th>
                                    <th>DESCRIPTIOM</th>
                                    <th>DATE</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($payments as $payment) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label="SN"><?= $sn ?></td>
                                        <td role="cell" data-label="REFERENCE"><?= strtoupper($payment["reference"]) ?></td>
                                        <td role="cell" data-label="AMOUNT : "><?= number_format($payment["debit"], 2) ?></td>
                                        <td class="text-uppercase" role="cell" data-label="STATUS : ">
                                            <span class="badge badge-<?= determineClass($payment["status"]) ?> p-2"><?= $payment["status"] ?></span>
                                        </td>
                                        <td role="cell" data-label="DESCRIPTION : "> <?= $payment["description"] ?> </td>
                                        <td role="cell" data-label="DATE : "> <?= $payment["created_at"] ?> </td>
                                        <td>
                                            <?php if ($payment["status"] === "complete") { ?>
                                                <button type="button" class="btn btn-primary btn-sm mx-1" id="verifybtn" data-reference="<?= $payment["reference"] ?>">
                                                    verify&nbsp;<i class="fas fa-check    "></i>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-primary btn-sm mx-1" disabled>
                                                    verify&nbsp;<i class="fas fa-check    "></i>
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

    <script src="/assets/js/client/orders.js"></script>
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>