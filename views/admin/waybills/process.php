<?php

use App\controllers\PackageController;
use App\controllers\InventoryController;
use App\controllers\PublicController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Process Waybill";
$currentnav = "waybills";
$pac = new PackageController();
$ic = new InventoryController();
$pc = new PublicController();
$package = $pac->getPackage();
$locations = $pc->getActiveLocations();
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
                    <li class="breadcrumb-item active">Process</li>
                </ol>
                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-body">
                            <form id="processwaybillform">
                                <p>Waybill Details</p>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="hidden" name="packageid" value="<?= $package["package"]["id"] ?>">
                                            <input type="text" class="form-control" placeholder="Enter title for package" name="title" value="<?= $package["package"]["package_title"] ?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transport Company</label>
                                            <input type="text" class="form-control" placeholder="Enter name of transport company" name="transportcompany" value="<?= $package["package"]["transport_company"] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Driver's Number <small class="text-muted">(optional)</small></label>
                                            <input type="tel" class="form-control" placeholder="080 XXXX XXXXX" value="<?= $package["package"]["driver_number"] ?>" name="drivernumber" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package Weight (kg)</label>
                                            <input type="number" pattern="\d+" class="form-control" placeholder="Enter package weight" name="weight" value="<?= $package["package"]["weight"] ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Destination</label>
                                            <select class="custom-select" name="destination">
                                                <?php foreach ($locations as $location) { ?>
                                                    <?php if ($package["package"]["destination"] === $location["id"]) { ?>
                                                        <option value="<?= $location["id"] ?>" selected><?= $location["location"] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $location["id"] ?>"><?= $location["location"] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <div><?= $package["package"]["description"] ?></div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Instructions</label>
                                            <div><?= $package["package"]["instructions"] ?></div>
                                        </div>
                                    </div>
                                </div>
                                <p class="m-0">Package Items</p>
                                <hr />
                                <section id="package-items" class="mt-5">
                                    <?php foreach ($package["packageitems"] as $item) { ?>
                                        <div class="row border border-light mt-2" style="position: relative;">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Item</label>
                                                    <select id="items" type="text" class="custom-select inventory-items" name="item[]" required readonly>
                                                        <option value="<?= $item["item_id"] ?>" selected><?= $item["name"] ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Unit Cost</label>
                                                    <input type="text" class="form-control unit-cost" placeholder="Enter unit cost of item" name="cost[]" value="<?= $item["unitcost"] ?>" readonly>
                                                    <input type="hidden" class="form-control" name="ids[]" value="<?= $item["id"] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Quantity</label>
                                                    <input type="number" min="0" class="form-control qty" placeholder="Enter Item quantity" name="quantity[]" value="<?= $item["quantity"] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </section>
                            </form>
                            <div class="d-flex justify-content-center align-items-center">
                                <?php if (in_array($package["package"]["status"], ["sent"])) { ?>
                                    <button type="submit" class="btn btn-primary mx-auto" style="width: 45%;" onclick="confirmChanges()">Save Changes <i class="fa fa-upload" aria-hidden="true"></i></button>
                                    <button type="submit" class="btn btn-success mx-auto" style="width: 45%;" onclick="confirmRecieve('<?= $package['package']['id'] ?>')">Mark As recieved <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script src="/assets/js/admin/waybill.js"></script>
</body>

</html>