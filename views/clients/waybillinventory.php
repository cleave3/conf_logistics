<?php

use App\controllers\InventoryController;

include_once "common/authheader.php";
$title = "Inventory";
$currentnav = "inventory";
$ic = new InventoryController();
$inventories = $ic->getClientInventory();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <a href="/clients/inventory/add" class="btn btn-sm btn-success">New Item <i class="fa fa-book"></i></a>
                <button class="btn btn-sm btn-danger" id="delbtn" disabled>Delete <i class="fa fa-trash"></i></button>
                <button class="btn btn-sm btn-dark" id="selectall" data-action="select">Select All <i class="fa fa-check"></i></button>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">WAYBILLED ITEMS</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">S/N</th>
                                    <th role="columnheader">ITEM&nbsp;NAME</th>
                                    <th role="columnheader">UNIT&nbsp;COST</th>
                                    <th role="columnheader">MEASURE</th>
                                    <th role="columnheader">QUANTITY</th>
                                    <th role="columnheader">DESCRIPTION</th>
                                    <th role="columnheader">CREATED&nbsp;AT</th>
                                    <th role="columnheader">UPDATED&nbsp;AT</th>
                                    <th role="columnheader"></th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($inventories as $inventory) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label=""><input type="checkbox" name="items[]" class="items" value="<?= $inventory["id"] ?>"> <span><?= $sn ?></span></td>
                                        <td role="cell" data-label="NAME : "><?= $inventory["name"] ?></td>
                                        <td role="cell" data-label="UNIT COST : "><?= number_format($inventory["unit_cost"], 2) ?></td>
                                        <td role="cell" data-label="MEASURE : "><?= $inventory["unit_measure"] ?></td>
                                        <td role="cell" data-label="QTY : "><?= number_format($inventory["quantity"]) ?></td>
                                        <td role="cell" data-label="DESCRIPTION : "><?= $inventory["description"] ?></td>
                                        <td role="cell" data-label="CREATED AT : "><?= date("Y-m-d, H:m:s a", strtotime($inventory["created_at"])) ?></td>
                                        <td role="cell" data-label="CREATED AT : "><?= empty($inventory["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($inventory["updated_at"])) ?></td>
                                        <td role="cell" data-label="" class="dropdown dropright">
                                            <a class="btn btn-sm btn-primary" href="/clients/inventory/edit?itemid=<?= $inventory["id"] ?>" title="Edit Item">&#9998;&nbsp;Edit</a>
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
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
    <script src="/assets/js/client/inventory.js"></script>
</body>

</html>