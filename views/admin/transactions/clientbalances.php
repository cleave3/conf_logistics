<?php

use App\controllers\TransactionController;

$base = __DIR__ . "/../";
include_once $base . "common/authheader.php";
$title = "Client Balances";
$currentnav = "transactions";
$tc = new TransactionController();
$balances = $tc->getClientBalances();
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
                    <li class="breadcrumb-item active">Client Balances</li>
                </ol>
                <div class="container">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-sm btn-primary mx-0" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                    </div>
                    <div class="print-container">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">CLIENT OUTSTANDING BALANCE AS AT <?= strtoupper(date("Y-m-d H:m:s a")) ?></h4>
                            </div>
                            <div class="card-body responsivetable table-responsive">
                                <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                                    <thead role="rowgroup">
                                        <tr role="row">
                                            <th role="columnheader">S/N</th>
                                            <th role="columnheader">SELLER</th>
                                            <th role="columnheader">SELLER&nbsp;TELEPHONE</th>
                                            <th role="columnheader">BALANCE (NGN)</th>
                                        </tr>
                                    </thead>
                                    <tbody role="rowgroup">
                                        <?php
                                        $sn = 1;
                                        foreach ($balances as $balance) {
                                        ?>
                                            <tr role="row">
                                                <td role="cell" data-label=""><?= $sn ?></td>
                                                <td role="cell" data-label="SELLER"><?= $balance["seller"] ?></td>
                                                <td role="cell" data-label="SELLER TELEPHONE"><?= $balance["sellertelephone"] ?></td>
                                                <td role="cell" class="text-<?= floatval($balance["balance"]) < 1 ? "danger" : "dark" ?>" data-label="BALANCE : "><?= number_format($balance["balance"]) ?></td>
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
            </div>
            <?php include_once $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include_once $base . "common/js.php" ?>
</body>

</html>