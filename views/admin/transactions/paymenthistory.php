<?php
$base = __DIR__ . "/../";
include_once $base . "common/authheader.php";
$title = "Transaction History";
$currentnav = "transactions";

use App\controllers\AgentController;
use App\controllers\ClientController;

$ac = new AgentController();
$cc = new ClientController();
$agents = $ac->getAllAgents();
$clients = $cc->getallclients();

$entities = array_merge($agents, $clients);
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
include_once $base . "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/transactions">Transactions</a>
                    </li>
                    <li class="breadcrumb-item active">Payments</li>
                </ol>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="target">Select Recipient</label>
                            <select id="target" class="custom-select">
                                <option value="all">All</option>
                                <?php foreach ($entities as $entity) { ?>
                                    <option value="<?= $entity["id"] ?>"><?= $entity["firstname"] ?> <?= $entity["lastname"] ?></option>
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
                    <button class="btn btn-primary mx-0 w-25" id="searchpayment">search Payments <i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                <div id="result-container" class="responsivetable table-responsive"></div>
            </div>
            <?php include_once $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include_once $base . "common/js.php" ?>

    <script src="/assets/js/admin/transaction.js"></script>
</body>

</html>