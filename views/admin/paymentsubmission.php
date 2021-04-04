<?php

use App\controllers\OrderController;

include_once "common/authheader.php";
$title = "Payment Submission";
$currentnav = "paymentsubmission";
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
                        <h4 class="card-title">AGENT PAYMENTS SUBMISSION</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th>ORDER&nbsp;ID</th>
                                    <th>AMOUNT</th>
                                    <th>FEE</th>
                                    <th>BALANCE</th>
                                    <th>AGENT</th>
                                    <th>SUBMISSION&nbsp;STATUS</th>
                                    <th>PAY&nbsp;METHOD</th>
                                    <th>PROOF</th>
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
                                        <td role="cell" data-label="AGENT : ">
                                            <?= $payment["agent"] ?>
                                        </td>
                                        <td role="cell" data-label="SUBMISSION STATUS : ">
                                            <span class="badge badge-<?= determineClass($payment["sendpayment_status"]) ?> p-2"><?= $payment["sendpayment_status"] ?></span>
                                        </td>
                                        <td role="cell" data-label="PAY METHOD : ">
                                            <?= $payment["payment_method"] ?>
                                        </td>
                                        <td role="cell" data-label="PROOF : ">
                                            <?php if (!empty($payment["proof"]) && $payment["payment_method"] != "paystack") { ?>
                                                <a title="download payment submission proof" class="btn btn-primary mx-0" href="/files/document/<?= $payment["proof"] ?>" download="paymentproof"><img src="/assets/icons/file.svg" width="15px" height="15px" /></a>
                                            <?php } else if ($payment["payment_method"] === "paystack") { ?>
                                                <span><?= $payment["proof"] ?></span>
                                            <?php } ?>
                                        </td>
                                        <td role="cell" data-label="UPDATED AT : ">
                                            <?= empty($payment["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($payment["updated_at"])) ?>
                                        </td>
                                        <td>
                                            <?php if ($payment["sendpayment_status"] !== "verified") { ?>
                                                <button onclick="confirmSubmissionVerify(<?= $payment['taskid'] ?>)" title="verify agent payment submission" class="btn btn-primary btn-sm mx-1">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-primary btn-sm mx-1" disabled>
                                                    <i class="fa fa-check" aria-hidden="true"></i>
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

    <script src="/assets/js/admin/payments.js"></script>
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