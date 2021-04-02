<?php

use App\controllers\PackageController;
use App\controllers\PublicController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Client Waybill";
$currentnav = "waybill";
include $base . "common/header.php";
$pc = new PublicController();
$pac = new PackageController();
$items = $pac->getClientPackageItemsWithDetails();
$states = $pc->allstates();
$paymentsource = ['RECIEVER', 'SENDER'];
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/clients/waybill">Waybills</a>
                    </li>
                    <li class="breadcrumb-item active">add</li>
                </ol>
                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <marquee class="text-warning">You can only send waybill request for items you have with us</marquee>
                        <div class="card-header">
                            <h5 class="card-title">WAYBILL REQUEST</h5>
                            <h6 class="text-center">Complete form to send a request for your items to be waybilled</h6>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <span class="badge badge-info p-3 px-1" style="font-size: 1.2rem;" id="waybillfee"><b>WAYBILL FEE:</b> 0.00</span>
                                <div>
                                    <small class="text-danger">Note: This amount might change due to quantity of item and other factors. We will communicate you when needed</small>
                                </div>
                            </div>
                            <form id="registerwaybillform" class="needs-validation" novalidate>
                                <p>Waybill Details</p>
                                <hr />
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select class="custom-select" name="state" id="state" required>
                                                <option value="">--SELECT STATE--</option>
                                                <?php foreach ($states as $state) { ?>
                                                    <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Destination city</label>
                                            <input type="text" class="form-control" placeholder="Enter destination in state" name="destination" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Payment Source</label>
                                            <select class="custom-select" name="paymentsource" required>
                                                <option value="">--SELECT PAYMENT SOURCE--</option>
                                                <?php foreach ($paymentsource as $source) { ?>
                                                    <option value="<?= $source ?>"><?= $source ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input type="text" class="form-control" placeholder="Description..." name="description" required>
                                        </div>
                                    </div>
                                </div>
                                <p class="m-0">waybill Items</p>
                                <button class="btn btn-sm btn-primary" id="addwaybillitem">Add Item</button>
                                <hr />
                                <section id="waybill-items" class="mt-5">
                                    <div class="row border border-light mt-2" style="position: relative;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select type="text" class="custom-select waybill-items" name="item[]" required>
                                                    <option selected value="">--SELECT ITEM--</option>
                                                    <?php foreach ($items as $item) { ?>
                                                        <option value="<?= $item["item_id"] ?>"><?= $item["name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div class="text-right"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" min="1" class="form-control qty" placeholder="Enter Item quantity" name="quantity[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="registerwaybillbtn">Submit</button>
                                </div>
                            </form>
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