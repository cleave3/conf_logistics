<?php

$base = __DIR__ . "/../";
include $base . "common/authheader.php";

$title = "Add Beneficiary";
$currentnav = "transactions";
include $base . "common/header.php";

$types = [
    ["label" => "Delivery Charge", "value" => "delivery_charge"],
    ["label" => "Waybill Charge", "value" => "waybill_charge"],
    ["label" => "Delivered Order", "value" => "delivered_order"],
    ["label" => "Payment", "value" => "payment"],
    ["label" => "Other Credit", "value" => "other_credit"],
    ["label" => "Other Debit", "value" => "other_debit"],
];
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
                    <li class="breadcrumb-item active">Add Transaction</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Add Transaction</h5>
                        </div>
                        <div class="card-body">
                            <form id="addtransactionform" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Target Type</label>
                                            <select id="type" class="custom-select" required>
                                                <option value="">--select target type--</option>
                                                <option value="clients">Clients</option>
                                                <option value="agents">Agents</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label id="entity-label">Who</label>
                                            <select id="entity" name="entity" class="custom-select" required></select>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary" id="addmore">Add More</button>
                                <div id="detail-container" class="row">
                                    <div class="col-12 mx-auto row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select name="type[]" class="custom-select" required>
                                                    <option value="">--transaction type--</option>
                                                    <?php foreach ($types as $type) { ?>
                                                        <option value="<?= $type["value"] ?>"><?= $type["label"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="text" name="amount[]" placeholder="0.00" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <input type="text" name="description[]" placeholder="description.." class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="savebtn">Save Transaction <i class="fas fa-save"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/admin/transaction.js"></script>
</body>

</html>