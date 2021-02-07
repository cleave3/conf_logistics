<?php

use App\controllers\InventoryController;

include_once "common/authheader.php";
$title = "Inventory";
$currentnav = "inventory";
$ic = new InventoryController();
$inventories = $ic->getClientInventory()["data"];
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <a href="/clients/inventory/add" class="btn btn-sm btn-success">New Item <i class="fa fa-book"></i></a>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ITEMS IN STOCK</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>ITEM NAME</th>
                                    <th>UNIT COST</th>
                                    <th>MEASURE</th>
                                    <th>QUANTITY</th>
                                    <th>DESCRIPTION</th>
                                    <th>CREATED AT</th>
                                    <th>UPDATED AT</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 1;
                                foreach ($inventories as $inventory) {
                                ?>
                                    <tr>
                                        <td><?= $sn ?></td>
                                        <td><?= $inventory["name"] ?></td>
                                        <td><?= number_format($inventory["unit_cost"], 2) ?></td>
                                        <td><?= $inventory["unit_measure"] ?></td>
                                        <td><?= number_format($inventory["quantity"]) ?></td>
                                        <td><?= $inventory["description"] ?></td>
                                        <td><?= date("Y-m-d H:m:s a", strtotime($inventory["created_at"])) ?></td>
                                        <td><?= empty($inventory["updated_at"]) ? "" : date("Y-m-d H:m:s a", strtotime($inventory["updated_at"])) ?></td>
                                        <td align="center" class="dropdown dropleft">
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">Detail</a>
                                                    <a class="dropdown-item" href="/clients/inventory/edit?itemid=<?= $inventory["id"] ?>" title="Edit Item">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                </div>
                                            </div>
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
            // fixedHeader: true
        });
    </script>
</body>

</html>