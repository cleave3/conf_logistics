<?php

use App\controllers\OrderController;

include_once "common/authheader.php";
$title = "Orders";
$currentnav = "orders";
$oc = new OrderController();
$orders = $oc->getClientOrders();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <a href="/clients/orders/add" class="btn btn-sm btn-success">New Order <i class="fa fa-book"></i></a>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">ORDERS</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">ORDER ID</th>
                                    <th role="columnheader">CUSTOMER</th>
                                    <th role="columnheader">TELEPHONE</th>
                                    <th role="columnheader">STATE</th>
                                    <th role="columnheader">CITY</th>
                                    <th role="columnheader">ADDRESS</th>
                                    <th role="columnheader">AMOUNT</th>
                                    <th role="columnheader">DELIVERY&nbsp;FEE</th>
                                    <th role="columnheader">BALANCE</th>
                                    <th role="columnheader">ORDER&nbsp;STATUS</th>
                                    <!-- <th role="columnheader">PAYMENT</th> -->
                                    <th role="columnheader">CREATED&nbsp;AT</th>
                                    <th role="columnheader">UPDATED&nbsp;AT</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($orders as $order) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label="ORDER ID">
                                            #<?= $order["id"] ?>
                                            <a class="btn btn-sm mx-1 btn-secondary" href="/clients/orders/details?orderid=<?= $order["id"] ?>" title="Order Details">
                                                <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                            </a>
                                        </td>
                                        <td role="cell" data-label="CUSTOMER : "><?= $order["customer"] ?></td>
                                        <td role="cell" data-label="TELEPHONE : "><?= $order["telephone"] ?></td>
                                        <td role="cell" data-label="STATE : "><?= $order["state"] ?></td>
                                        <td role="cell" data-label="CITY : "><?= $order["city"] ?></td>
                                        <td role="cell" data-label="ADDRESS : "><?= $order["address"] ?></td>
                                        <td role="cell" data-label="AMOUNT : "><?= number_format($order["totalamount"], 2) ?></td>
                                        <td role="cell" data-label="DELIVERY FEE : "><?= number_format($order["delivery_fee"], 2) ?></td>
                                        <td role="cell" data-label="EXPECTED PAYMENT : ">
                                            <?= number_format(($order["totalamount"] - $order["delivery_fee"]), 2) ?>
                                        </td>
                                        <td class="text-uppercase" role="cell" data-label="ORDER STATUS : ">
                                            <span class="badge badge-<?= determineClass($order["status"]) ?> p-2"><?= $order["status"] ?></span>
                                        </td>
                                        <!-- <td class="text-uppercase" role="cell" data-label="PAYMENT STATUS : ">
                                            <span class="badge badge-<?= determineClass($order["payment_status"]) ?> p-2"><?= $order["payment_status"] ?></span>
                                        </td> -->
                                        <td role="cell" data-label="CREATED AT : ">
                                            <?= date("Y-m-d, H:m:s a", strtotime($order["created_at"])) ?>
                                        </td>
                                        <td role="cell" data-label="UPDATED AT : ">
                                            <?= empty($order["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($order["updated_at"])) ?>
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
</body>

</html>