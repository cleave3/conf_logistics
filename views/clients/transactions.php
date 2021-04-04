<?php
include_once "common/authheader.php";
$title = "Transaction History";
$currentnav = "transactions";

$statuses = [
    ["value" => "all", "label" => ucwords("all")],
    ["value" => "complete", "label" => ucwords("complete")],
    ["value" => "failed", "label" => ucwords("failed")],
    ["value" => "pending", "label" => ucwords("pending")],
    ["value" => "reversed", "label" => ucwords("reversed")],
    ["value" => "verified", "label" => ucwords("verified")],
];
$types = [
    ["value" => "all", "label" => ucwords("all")],
    ["label" => "Delivery Charge", "value" => "delivery_charge"],
    ["label" => "Waybill Charge", "value" => "waybill_charge"],
    ["label" => "Delivered Order", "value" => "delivered_order"],
    ["label" => "Payment", "value" => "payment"],
    ["label" => "Other Credit", "value" => "other_credit"],
    ["label" => "Other Debit", "value" => "other_debit"],
];
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <div class="card-header">
                    <h4 class="card-title">TRANSACTION HISTORY</h4>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" class="custom-select">
                                <?php foreach ($types as $type) { ?>
                                    <option value="<?= $type["value"] ?>"><?= $type["label"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" class="custom-select">
                                <?php foreach ($statuses as $status) { ?>
                                    <option value="<?= $status["value"] ?>"><?= $status["label"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="startdate">Start Date</label>
                            <input type="date" id="startdate" class="form-control" value="<?= date("Y-m-d", strtotime("-1month")) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="enddate">End Date</label>
                            <input type="date" id="enddate" class="form-control" value="<?= date("Y-m-d") ?>">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mx-0 w-25" id="searchbtn">search <i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                <div id="result-container" class="responsivetable table-responsive"></div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>

    <script src="/assets/js/client/transaction.js"></script>
</body>

</html>