<?php

use App\controllers\PublicController;
use App\controllers\WaybillController;
use App\controllers\PackageController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Client Waybill";
$currentnav = "waybill";
include $base . "common/header.php";
$pc = new PublicController();
$wc = new WaybillController();
$pac = new PackageController();
$states = $pc->allstates();
$waybilldata = $wc->clientwaybill();
$items = $pac->getClientPackageItemsWithDetails();
$waybill = $waybilldata["waybill"];
$waybillitems = $waybilldata["waybillitems"];
$paymentsource = ['RECIEVER', 'SENDER'];
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <?php if ($waybill) { ?>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/clients/waybill">Waybills</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                    <?php if ($waybill["status"] !== "pending") { ?>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-center">THIS WAYBILL HAS BEEN <?= strtoupper($waybill["status"]) ?></h6>
                                <div class="d-flex justify-content-center">
                                    <a class="my-2 btn btn-dark" href="/clients/waybill"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-12 mx-auto">
                            <div class="card card-user">
                                <marquee class="text-warning">You can only send waybill request for items you have with us</marquee>
                                <div class="card-header">
                                    <h5 class="card-title">EDIT WAYBILL REQUEST</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-primary mx-0" data-toggle="modal" data-target="#additemmodal">Add Item</button>
                                    </div>
                                    <div class="">
                                        <span class="badge badge-info p-3 px-1" style="font-size: 1.2rem;" id="waybillfee"><b>WAYBILL FEE:</b> NGN <?= number_format($waybill["fee"], 2) ?></span>
                                        <div>
                                            <small class="text-danger">Note: This amount might change due to quantity of item and other factors. We will communicate you when needed</small>
                                        </div>
                                    </div>
                                    <form id="editwaybillform" class="needs-validation" novalidate>
                                        <p>Waybill Details</p>
                                        <hr />
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <select class="custom-select" name="state" id="state" required>
                                                        <option value="">--SELECT STATE--</option>
                                                        <?php foreach ($states as $state) { ?>
                                                            <?php if ($state["id"] === $waybill["state_id"]) { ?>
                                                                <option value="<?= $state["id"] ?>" selected><?= $state["state"] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Destination city</label>
                                                    <input type="text" class="form-control" placeholder="Enter destination in state" value="<?= $waybill["destination"] ?>" name="destination" required>
                                                    <input type="hidden" value="<?= $waybill["id"] ?>" name="waybillid" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payment Source</label>
                                                    <select class="custom-select" name="paymentsource" required>
                                                        <?php foreach ($paymentsource as $source) { ?>
                                                            <?php if ($source === $waybill["payment_source"]) { ?>
                                                                <option value="<?= $source ?>" selected><?= $source ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $source ?>"><?= $source ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <input type="text" class="form-control" placeholder="Description..." value="<?= $waybill["description"] ?>" name="description" required>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="m-0">waybill Items</p>
                                        <hr />
                                        <section id="waybill-items" class="mt-5">
                                            <?php foreach ($waybillitems as $item) { ?>
                                                <div class="row border border-light mt-2" style="position: relative;" id="<?= $item["id"] ?>">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Item</label>
                                                            <input type="text" class="form-control" value="<?= $item["name"] ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" value="<?= $item["quantity"] ?>" readonly>
                                                                <div class="input-group-append">
                                                                    <span class="btn btn-danger m-0 delwaybill" data-id="<?= $item["id"] ?>"><i class="delwaybill fa fa-times" aria-hidden="true" data-id="<?= $item["id"] ?>"></i></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </section>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary w-50 mx-auto" id="editwaybillbtn">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="d-flex justify-content-center align-items-center my-5" style="height: 300px;">
                        <div>
                            <p class="text-center font-weight-bold">Waybill not found</p>
                            <img src="/assets/icons/empty.svg" class="img-fluid" width="200px" height="200px" />
                            <div class="d-flex justify-content-center">
                                <a class="my-2 btn btn-dark" href="/clients/waybill"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="additemmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add More Items to list</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="additemform" class="needs-validation" novalidate>
                                <div class="row border border-light mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Item</label>
                                            <select type="text" class="custom-select waybill-items" name="item" required>
                                                <option selected value="">--SELECT ITEM--</option>
                                                <?php foreach ($items as $item) { ?>
                                                    <option value="<?= $item["item_id"] ?>"><?= $item["name"] ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="text-right" id="qtydiv"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" min="1" class="form-control qty" placeholder="Enter Item quantity" name="quantity" required>
                                            <input type="hidden" value="<?= $waybill["id"] ?>" name="waybillid" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary w-100 mx-auto" id="additembtn">Submit</button>
                                        </div>
                                    </div>
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