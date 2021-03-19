<?php

use App\controllers\OrderController;

include $base . "common/authheader.php";
$oc = new OrderController();
$order = $oc->getOrderDetails($_GET["orderid"]);
$base = __DIR__ . "/../";
$title = "Order Details";
$currentnav = "orders";
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
                        <a href="/clients/orders">Orders</a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
                <button class="btn btn-sm btn-primary mx-1" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                <button type="button" class="btn btn-primary btn-sm mx-1" data-toggle="modal" data-target="#orderhistorymodal">
                    <img src="/assets/icons/history.svg" width="15px" height="15px" /> Order history
                </button>
                <?php if (!in_array($order["order"]["status"], ["delivered", "intransit", "cancelled"])) { ?>
                    <button type="button" class="btn btn-danger btn-sm mx-1" id="cancelbtn" data-orderid="<?= $order["order"]["id"] ?>">
                        <img src="/assets/icons/forbidden.svg" width="15px" height="15px" /> Cancel Order
                    </button>
                <?php } ?>

                <!-- <?php if (in_array($order["order"]["payment_status"], ["paid"])) { ?>
                    <button type="button" class="btn btn-transparent btn-sm mx-1" id="verifybtn" data-orderid="<?= $order["order"]["id"] ?>">
                        <img src="/assets/icons/money.svg" width="15px" height="15px" /> Verify Payment
                    </button>
                <?php } ?> -->
                <div class="order print-container">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-left">Seller: <?= strtoupper($order["order"]["companyname"]) ?></h3>
                            <p class="text-left">Seller Telephone: <?= $order["order"]["companytelephone"] ?></p>
                        </div>
                        <div class="card-body">
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="3">ORDER DETAIL</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Date : </b> <?= date("Y-m-d H:m:s a", strtotime($order["order"]["created_at"])) ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" class="text-uppercase">
                                            <b>Order Status : </b><span class="badge badge-<?= determineClass($order["order"]["status"]) ?> p-2"><?= $order["order"]["status"] ?></span>
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Order Amount : </b> <?= number_format($order["order"]["totalamount"], 2) ?><br />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Customer : </b> <?= $order["order"]["customer"] ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="2">
                                            <b>Customer Telephone : </b> <?= $order["order"]["telephone"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Description : </b> <?= $order["order"]["description"] ?><br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">State</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">City</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Address</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $order["order"]["state"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $order["order"]["city"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $order["order"]["address"] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center p-2">ORDER ITEMS</div>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">S/N</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">ITEM</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">QTY</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">COST</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">Total</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    $grandtotal = 0;
                                    foreach ($order["orderitems"] as $item) {
                                        $total = floatval($item["cost"]) * floatval($item["quantity"]);
                                    ?>
                                        <tr>
                                            <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"><?= $sn ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["name"] ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["quantity"] ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($item["cost"], 2) ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($total, 2) ?></td>
                                        </tr>
                                    <?php
                                        $grandtotal += $total;
                                        $sn++;
                                    }
                                    ?>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="4">ITEMS TOTAL</td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($grandtotal, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="4">DELIVERY FEE</td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;" class="text-danger">-<?= number_format($order["order"]["delivery_fee"], 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="4">FINAL BALANCE</td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format((floatval($grandtotal) - floatval($order["order"]["delivery_fee"])), 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="orderhistorymodal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                    <thead>
                                        <tr>
                                            <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="2">ORDER HISTORY</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order["orderhistory"] as $history) { ?>
                                            <tr>
                                                <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                                    <?= date("Y-m-d H:m:s a", strtotime($history["created_at"])) ?><br />
                                                </td>
                                                <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                                    <?= $history["description"] ?><br />
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/client/orders.js"></script>
</body>

</html>