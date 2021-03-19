<?php

use App\controllers\PackageController;

include_once "common/authheader.php";
$title = "Inventory";
$currentnav = "inventory";
$pc = new PackageController();
$inventories = $pc->getPackageItemsWithDetails();
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
                        <h4 class="card-title">ITEMS IN STOCK</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">S/N</th>
                                    <th role="columnheader">ITEM&nbsp;NAME</th>
                                    <th role="columnheader">UNIT&nbsp;COST</th>
                                    <th role="columnheader">QUANTITY</th>
                                    <th role="columnheader">MEASURE</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($inventories as $inventory) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label=""><?= $sn ?></td>
                                        <td role="cell" data-label="NAME : "><?= $inventory["name"] ?></td>
                                        <td role="cell" data-label="UNIT COST : "><?= number_format($inventory["unit_cost"], 2) ?></td>
                                        <td role="cell" data-label="QTY : "><?= number_format($inventory["quantity"]) ?></td>
                                        <td role="cell" data-label="MEASURE : "><?= $inventory["unit_measure"] ?></td>
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
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>