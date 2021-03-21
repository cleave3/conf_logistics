<?php

use App\controllers\OrderController;

include_once "common/authheader.php";
$title = "Orders";
$currentnav = "orders";
$oc = new OrderController();
$orders = $oc->getAllOrders();
// exit(json_encode($orders));
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
                        <h4 class="card-title">ORDERS</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                            <thead role="rowgroup">
                                <tr role="row">
                                    <th role="columnheader">ORDER&nbsp;ID</th>
                                    <th role="columnheader">CLIENT</th>
                                    <th role="columnheader">CUSTOMER</th>
                                    <th role="columnheader">TELEPHONE</th>
                                    <th role="columnheader">STATE</th>
                                    <th role="columnheader">CITY</th>
                                    <th role="columnheader">ADDRESS</th>
                                    <th role="columnheader">AMOUNT</th>
                                    <th role="columnheader">DELIVERY&nbsp;FEE</th>
                                    <th role="columnheader">BALANCE</th>
                                    <th role="columnheader">STATUS</th>
                                    <!-- <th role="columnheader">PAYMENT</th> -->
                                    <th role="columnheader">CREATED&nbsp;AT</th>
                                    <th role="columnheader">UPDATED&nbsp;AT</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup" id="inventorylist">
                                <?php
                                $sn = 1;
                                foreach ($orders as $order) {
                                    // foreach ($orders["data"] as $order) {
                                ?>
                                    <tr role="row">
                                        <td role="cell" data-label="ORDER ID">
                                            #<?= $order["id"] ?>
                                            <a class="btn btn-sm mx-1 btn-secondary" href="/admin/orders/details?orderid=<?= $order["id"] ?>" title="Order Details">
                                                <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                            </a>
                                        </td>
                                        <td role="cell" data-label="CLIENT : "><?= $order["companyname"] ?></td>
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
                <!--pagination -->
                <!-- <div class="d-flex justify-content-between">
                    <div class="">
                        <div class="dataTables_info" id="resulttable_info" role="status" aria-live="polite">Showing <?= $orders["offset"] + 1 ?> to <?= $orders["rows"] ?> of <?= $orders["totalrecords"] ?> entries</div>
                    </div>
                    <div class="">
                        <div class="dataTables_paginate paging_simple_numbers" id="resulttable_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous" id="resulttable_previous">
                                    <a href="/admin/orders?page=1" aria-controls="resulttable" data-dt-idx="0" tabindex="0" class="page-link">Firstpage </a>
                                </li>
                                <?php if (($orders["currentpage"] - 2) > 0) { ?>
                                    <li class="paginate_button page-item">
                                        <a href="/admin/orders?page=<?= $orders["currentpage"] - 2 ?>" aria-controls="resulttable" data-dt-idx="1" tabindex="0" class="page-link"><?= $orders["currentpage"] - 2 ?></a>
                                    </li>
                                <?php } ?>
                                <?php if (($orders["currentpage"] - 1) > 0) { ?>
                                    <li class="paginate_button page-item">
                                        <a href="/admin/orders?page=<?= $orders["currentpage"] - 1 ?>" aria-controls="resulttable" data-dt-idx="1" tabindex="0" class="page-link"><?= $orders["currentpage"] - 1 ?></a>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button page-item active">
                                    <a href="/admin/orders?page=<?= $orders["currentpage"] ?>" aria-controls="resulttable" data-dt-idx="1" tabindex="0" class="page-link"><?= $orders["currentpage"] ?></a>
                                </li>
                                <?php if (($orders["currentpage"] + 1) < $orders["totalpages"]) { ?>
                                    <li class="paginate_button page-item">
                                        <a href="/admin/orders?page=<?= $orders["currentpage"] + 1 ?>" aria-controls="resulttable" data-dt-idx="1" tabindex="0" class="page-link"><?= $orders["currentpage"] + 1 ?></a>
                                    </li>
                                <?php } ?>
                                <?php if (($orders["currentpage"] + 2) < $orders["totalpages"]) { ?>
                                    <li class="paginate_button page-item">
                                        <a href="/admin/orders?page=<?= $orders["currentpage"] + 2 ?>" aria-controls="resulttable" data-dt-idx="1" tabindex="0" class="page-link"><?= $orders["currentpage"] + 2 ?></a>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button page-item next" id="resulttable_next">
                                    <a href="/admin/orders?page=<?= $orders["totalpages"] ?>" aria-controls="resulttable" data-dt-idx="2" tabindex="0" class="page-link">Lastpage</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> -->
                <!--pagination -->
            </div>

            <?php include_once "common/footer.php" ?>
        </div>
    </div>
    <?php include_once "common/js.php" ?>
    <!-- <script src="/assets/js/client/package.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true,
            "order": [
                [0, "desc"]
            ]
        });
    </script>
</body>

</html>