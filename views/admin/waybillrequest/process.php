<?php

use App\controllers\WaybillController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Client Waybill";
$currentnav = "waybill";
include $base . "common/header.php";
$wc = new WaybillController();
$waybilldata = $wc->waybill();
$waybill = $waybilldata["waybill"];
$waybillitems = $waybilldata["waybillitems"];

$statuses = [["value" => "sent", "label" => ucwords("sent")], ["value" => "pending", "label" => ucwords("pending")]];
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
                            <a href="/admin/waybillrequests">Waybill Requests</a>
                        </li>
                        <li class="breadcrumb-item active">Process</li>
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
                                <div class="card-header">
                                    <h5 class="card-title">PROCESS WAYBILL REQUEST</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mx-0" id="processwaybillbtn">Save Changes</button>
                                    </div>
                                    <form id="processwaybillform" class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Driver Number <small class="text-muted">(optional)</small></label>
                                                    <input type="text" class="form-control" placeholder="Enter Driver number" value="<?= $waybill["driver_number"] ?>" name="drivernumber">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Transport Company</label>
                                                    <input type="text" class="form-control" placeholder="Enter transport company name" value="<?= $waybill["transport_company"] ?>" name="transportcompany" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status" class="mx-1">Update Order status</label>
                                                    <select name="status" class="custom-select" required>
                                                        <?php foreach ($statuses as $status) { ?>
                                                            <?php if ($waybill["status"] === $status["value"]) { ?>
                                                                <option value="<?= $status["value"] ?>" selected><?= $status["label"] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $status["value"] ?>"><?= $status["label"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" value="<?= $waybill["destination"] ?>" name="state" value="<?= $waybill["state"] ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Destination city</label>
                                                    <input type="text" class="form-control" value="<?= $waybill["destination"] ?>" name="destination" readonly>
                                                    <input type="hidden" value="<?= $waybill["id"] ?>" name="waybillid" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payment Source</label>
                                                    <input type="text" class="form-control" value="<?= $waybill["payment_source"] ?>" name="payment_source" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <input type="text" class="form-control" placeholder="Description..." value="<?= $waybill["description"] ?>" name="description" readonly>
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </section>
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

            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/admin/waybillrequest.js"></script>
</body>

</html>