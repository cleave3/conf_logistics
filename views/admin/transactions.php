<?php

include_once "common/authheader.php";
$title = "Transactions";
$currentnav = "transactions";
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content" style="height: calc(100vh - 150px); display: grid; place-items: center;">
                <div class="row w-100">
                    <div class="col-12">
                        <h3 class="text-center">TRANSACTIONS PANEL <i class="fa fa-credit-card" aria-hidden="true"></i></h3>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/beneficiaries/add">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/add-group.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">New Beneficiary</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/beneficiaries">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/user.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">Beneficiaries</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/add">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/transaction.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">New Transaction</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/history">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/transaction_history.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">Transaction History</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/newpayment">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/cashless-payment.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">New Payment</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <a class="text-muted card-link" href="/admin/transactions/paymenthistory">
                            <div class="card light-blue">
                                <div class="card-body">
                                    <div class="w-100 d-flex justify-content-center">
                                        <img src="/assets/icons/valid.svg" class="img-fluid" height="100px" />
                                    </div>
                                    <div class="text-center p-2 font-weight-bold">Payment History</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
</body>

</html>