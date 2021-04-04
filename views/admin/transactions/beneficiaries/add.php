<?php

use App\controllers\PublicController;

$base = __DIR__ . "/../../";
include $base . "common/authheader.php";

$pc = new PublicController();

$title = "Add Beneficiary";
$currentnav = "transactions";
$banks =  $pc->getBankList()["data"];
include $base . "common/header.php";

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
                    <li class="breadcrumb-item active">Add Beneficiaries</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Submit form to create a new transfer recipient</h5>
                        </div>
                        <div class="card-body">
                            <form id="addbeneficiaryform">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Beneficiary Type</label>
                                            <select id="type" class="custom-select" required>
                                                <option value="">--select beneficiary type--</option>
                                                <option value="clients">Clients</option>
                                                <option value="agents">Agents</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label id="entity-label">Select Beneficiary</label>
                                            <select id="entity" name="entity" class="custom-select" required></select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Select Bank</label>
                                            <select name="bankcode" id="bankcode" class="custom-select" required>
                                                <option value="">--SELECT BANK--</option>
                                                <?php foreach ($banks as $bank => $value) { ?>
                                                    <option value="<?= $value["bankcode"] ?>"><?= $value["bankname"] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="accountnumber">Account Number</label>
                                            <input id="accountnumber" type="text" class="form-control" pattern="\d{10}" placeholder="accountnumber" name="accountnumber" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="accountname">Account Name</label>
                                            <div class="input-group">
                                                <input id="accountname" type="text" class="form-control" placeholder="account name" name="accountname" readonly required>
                                                <div class="input-group-append">
                                                    <button id="verifybtn" class="btn btn-success m-0">verify</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="savebtn">Save Beneficiary</button>
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
    <script src="/assets/js/admin/beneficiaries.js"></script>
</body>

</html>