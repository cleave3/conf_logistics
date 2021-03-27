<?php

use App\controllers\OrderController;
use App\controllers\TaskController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$oc = new OrderController();
$order = $oc->getOrderDetails($_GET["orderid"]);
$task = new TaskController();
$title = "Delivery Details";
$currentnav = "deliveries";

$statuses = [
    ["value" => "cancelled", "label" => ucwords("cancelled")],
    ["value" => "confirmed", "label" => ucwords("confirmed")],
    ["value" => "delivered", "label" => ucwords("delivered")],
    ["value" => "intransit", "label" => ucwords("intransit")],
    ["value" => "processing", "label" => ucwords("Processing")],
    ["value" => "noresponse", "label" => ucwords("noresponse")],
    ["value" => "pending", "label" => ucwords("pending")],
    ["value" => "rescheduled", "label" => ucwords("rescheduled")],
];
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
                        <a href="/agents/deliveries">Deliveries</a>
                    </li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
                <?php if ($order["order"]) { ?>
                    <div class="d-flex flex-wrap justify-content-between">
                        <div>
                            <button class="btn btn-sm btn-primary mx-1" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                            <button type="button" class="btn btn-primary btn-sm mx-1" data-toggle="modal" data-target="#orderhistorymodal">
                                <img src="/assets/icons/history.svg" width="15px" height="15px" /> Order history
                            </button>
                            <?php if ($task->getTaskByOrderId($order["order"]["id"])["sendpayment"] !== "YES") { ?>
                                <a href="/agents/deliveries/payment?orderid=<?= $order["order"]["id"] ?>" class="btn btn-transparent btn-sm mx-1">
                                    <img src="/assets/icons/money.svg" width="15px" height="15px" />Payment
                                </a>
                            <?php } ?>
                        </div>
                        <div>
                            <?php if ($order["order"]["status"] !== "delivered") { ?>
                                <form class="form-inline" id="statusform">
                                    <div class="form-group">
                                        <label for="status" class="mx-1">Update Order status</label>
                                        <select id="status" name="status" class="custom-select">
                                            <?php foreach ($statuses as $status) { ?>
                                                <?php if ($order["order"]["status"] === $status["value"]) { ?>
                                                    <option value="<?= $status["value"] ?>" selected><?= $status["label"] ?></option>
                                                <?php } else { ?>
                                                    <option value="<?= $status["value"] ?>"><?= $status["label"] ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <input name="orderid" type="hidden" value="<?= $order["order"]["id"] ?>" />
                                        <button class="btn btn-info mx-1">update</button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
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
                                            <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="4">TOTAL</td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($grandtotal, 2) ?></td>
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
                <?php } else { ?>
                    <div class="d-flex justify-content-center align-items-center my-5" style="height: 300px;">
                        <div>
                            <p class="text-center font-weight-bold">Content not found</p>
                            <img src="/assets/icons/empty.svg" class="img-fluid" width="200px" height="200px" />
                            <div class="d-flex justify-content-center">
                                <a class="my-2 btn btn-dark" href="/agents/deliveries"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/agents/detail.js"></script>
</body>

</html>