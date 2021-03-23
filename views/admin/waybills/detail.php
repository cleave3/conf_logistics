<?php

use App\controllers\PackageController;

include $base . "common/authheader.php";
$pc = new PackageController();
$package = $pc->getPackage();
$base = __DIR__ . "/../";
$title = "Waybills";
$currentnav = "waybills";
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
                        <a href="/admin/waybills">Waybills</a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-sm btn-primary mx-0" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                    <?php if (!empty($package["package"]["image"])) { ?>
                        <a class="btn btn-danger mx-0" href="/files/document/<?= $package['package']['image'] ?>" download="waybilldocument">Download Attachment <img src="/assets/icons/file.svg" width="15px" height="15px" /></a>
                    <?php } ?>
                </div>
                <div class="waybill print-container">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title text-center"><?= strtoupper($package["owner"]["companyname"]) ?></h3>
                            <p class="text-center">Address: <?= $package["owner"]["address"] ?> | Telephone: <?= $package["owner"]["telephone"] ?></p>
                            <p class="text-left text-uppercase"><b>Sender :</b> <?= $package["owner"]["firstname"] ?> <?= $package["owner"]["lastname"] ?></p>

                        </div>
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
                                            <b>Date : </b> <?= date("Y-m-d H:m:s a", strtotime($package["owner"]["created_at"])) ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" class="text-uppercase">
                                            <b>PAckage Status : </b><span class="badge badge-<?= determineClass($package["package"]["status"]) ?> p-2"><?= $package["package"]["status"] ?></span>
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Package Title : </b> <?= $package["package"]["package_title"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Description : </b> <?= $package["package"]["description"] ?><br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Transport Company</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Driver's Number</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Package Weight (KG)</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $package["package"]["transport_company"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $package["package"]["driver_number"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $package["package"]["weight"] ?></td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"> <?= $package["package"]["status"] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center p-2">PACKAGE ITEMS</div>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">S/N</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;">ITEM</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">Quantity</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">COST</td>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 15px; color: #222222;">Total</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    $grandtotal = 0;
                                    foreach ($package["packageitems"] as $item) {
                                        $total = floatval($item["unitcost"]) * floatval($item["quantity"]);
                                    ?>
                                        <tr>
                                            <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;"><?= $sn ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["name"] ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= $item["quantity"] ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($item["unitcost"], 2) ?></td>
                                            <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($total, 2) ?></td>
                                        </tr>
                                    <?php
                                        $grandtotal += $total;
                                        $sn++;
                                    }
                                    ?>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="4">GRAND TOTAL</td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: right; padding: 15px;"><?= number_format($grandtotal, 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="2">INSTRUCTIONS</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="2">
                                            <?= $package["package"]["instructions"] ?><br />
                                        </td>
                                    </tr>
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
    <script src="/assets/js/client/package.js"></script>
</body>

</html>