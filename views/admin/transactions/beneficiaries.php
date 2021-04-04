<?php

use App\controllers\TransactionController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";

$tc = new TransactionController();

$title = "Beneficiaries";
$currentnav = "transactions";
$beneficiaries = $tc->getBeneficiaries();
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
                    <li class="breadcrumb-item active">Beneficiaries</li>
                </ol>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">BENEFICIARIES</h4>
                        <p>This is a list of all your transfer recipients</p>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th>S/N</th>
                                    <th>BENEFICIARY</th>
                                    <th>BANK&nbsp;NAME</th>
                                    <th>ACCOUN&nbsp;NUMBER</th>
                                    <th>ACCOUN&nbsp;NAME</th>
                                    <th>CREATOR</th>
                                    <th>CREATED&nbsp;AT</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                <?php
                                $sn = 1;
                                foreach ($beneficiaries as $beneficiary) {
                                ?>
                                    <tr role="row">
                                        <td data-label="SN"><?= $sn ?></td>
                                        <td data-label="BENEFICIARY : "><?= $beneficiary["agent"] ?? $beneficiary["client"] ?></td>
                                        <td data-label="BANK NAME : "><?= $beneficiary["bankname"] ?></td>
                                        <td data-label="ACCOUNT NUMBER : "><?= $beneficiary["accountnumber"] ?></td>
                                        <td data-label="ACCOUNT NAME : "><?= $beneficiary["accountname"] ?></td>
                                        <td data-label="CREATOR : "><?= $beneficiary["creator"] ?></td>
                                        <td data-label="CREATED AT : "><?= date("Y-m-d H:m:s a", strtotime($beneficiary["created_at"])) ?></td>
                                    </tr>
                                <?php
                                    $sn++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true,
        });
    </script>
</body>

</html>