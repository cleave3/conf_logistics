<?php

use App\controllers\WaybillController;

include $base . "common/authheader.php";
$wc = new WaybillController();
$base = __DIR__ . "/../";
$title = "Waybill Details";
$currentnav = "waybill";
$waybilldata = $wc->clientwaybill();
$waybill = $waybilldata["waybill"];
$waybillitems = $waybilldata["waybillitems"];
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
                        <a href="/clients/waybill">waybills</a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
                <button class="btn btn-sm btn-primary mx-0" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                <?php if (in_array($waybill["status"], ["pending"])) { ?>
                    <button type="button" class="btn btn-danger btn-sm mx-1" id="cancelbtn" data-waybillid="<?= $waybill["id"] ?>">
                        <img src="/assets/icons/forbidden.svg" width="15px" height="15px" /> Cancel Waybill
                    </button>
                <?php } ?>
                <div class="waybill print-container">
                    <div class="card">
                        <div class="card-body">
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="3">WAYBILL DETAIL</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Date : </b> <?= date("Y-m-d H:m:s a", strtotime($waybill["created_at"])) ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" class="text-uppercase">
                                            <b>waybill Status : </b><span class="badge badge-<?= determineClass($waybill["status"]) ?> p-2"><?= $waybill["status"] ?></span>
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Updated At: </b> <?= $waybill["updated_at"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Description : </b> <?= $waybill["description"] ?><br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Transport Company</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Driver's Number</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $waybill["transport_company"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $waybill["driver_number"] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center p-2">WAYBILL ITEMS</div>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">S/N</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">ITEM</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">Quantity</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    $grandtotal = 0;
                                    foreach ($waybillitems as $item) {
                                        $total = floatval($item["unitcost"]) * floatval($item["quantity"]);
                                    ?>
                                        <tr>
                                            <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"><?= $sn ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["name"] ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["quantity"] ?></td>
                                        </tr>
                                    <?php $sn++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/client/waybill.js"></script>
</body>

</html>