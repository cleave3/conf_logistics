<?php

use App\controllers\TransactionController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Transfers";
$currentnav = "transactions";

$tc = new TransactionController();
$recipients = $tc->getBeneficiaries();
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
                    <li class="breadcrumb-item active">Payment</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h4 class="card-title">INTER BANK TRANSFER</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-end" id="finalisebtn-container"></div>
                            <form id="transferform" class="needs-validation" novalidate>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label id="entity-label">Select Recipients</label>
                                            <select id="entity" name="entity" class="custom-select" required>
                                                <option value="" disabled selected>--select recipient--</option>
                                                <?php foreach ($recipients as $recipient) { ?>
                                                    <option value="<?= $recipient["entity_id"] ?>"><?= $recipient["agent"] ??  $recipient["client"] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input id="amount" type="text" class="form-control" placeholder="amount" name="amount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Outstanding Balance</label>
                                            <input id="balance" type="text" class="form-control" placeholder="outstanding balance" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Bank</label>
                                            <input id="bank" type="text" class="form-control" placeholder="bank" name="bank" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="accountnumber">Account Number</label>
                                            <input id="accountnumber" type="text" class="form-control" placeholder="accountnumber" name="accountnumber" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="accountname">Account Name</label>
                                            <input id="accountname" type="text" class="form-control" placeholder="account name" name="accountname" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="accountname">Purpose of transfer</label>
                                            <input id="purpose" type="text" class="form-control" placeholder="purpose of transfer" name="purpose" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="initiatebtn">Initiate Transfer <i class="fas fa-paper-plane "></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="otpmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form id="otpform" class="needs-validation" novalidate>
                                    <input type="hidden" id="transfercode" name="transfercode">
                                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" minlength="6" maxlength="6" class="form-control p-2 text-center" style="font-size: 1.5rem;" required>
                                    <button type="submit" class="btn btn-success w-100 mx-auto" id="otpbtn">confirm Transfer <i class="fas fa-paper-plane    "></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script>
        formatCurrencyInput(["#amount"])
    </script>
    <script src="/assets/js/admin/transfers.js"></script>
</body>

</html>