<?php

use App\controllers\InventoryController;

if (!isset($_GET["itemid"])) {
    header("location:inventory");
    exit;
}
$ic = new InventoryController();

$item = $ic->getInventoryItem()["data"];

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Edit Item";
$currentnav = "inventory";
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
                        <a href="/clients/inventory">Inventory</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Update <?= $item["name"] ?></h5>
                        </div>
                        <div class="card-body">
                            <form id="updateinventoryform">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Item Name</label>
                                            <input type="hidden" name="itemid" value="<?= $_GET["itemid"] ?>">
                                            <input type="text" id="name" class="form-control" placeholder="Enter product name" name="name" value="<?= $item["name"] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity on Hand</label>
                                            <input type="number" pattern="\d" min="1" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" value="<?= $item["quantity"] ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unit Cost</label>
                                            <input type="text" class="form-control" name="cost" id="cost" placeholder="Enter Unit Cost" value="<?= $item["unit_cost"] ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity to Add</label>
                                            <input type="number" pattern="\d" min="0" id="in" name="in" class="form-control" placeholder="Enter quantity" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity to Remove</label>
                                            <input type="number" pattern="\d" min="0" id="out" name="out" class="form-control" placeholder="Enter quantity" value="0">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Unit Measure</label>
                                            <select name="measure" class="custom-select">
                                                <option value="">-- SELECT MEASURE --</option>
                                                <?php
                                                $measures = ["PIECES", "CARTONS", "PACKETS", "GALLONS", "CRATES"];
                                                foreach ($measures as $measure) { ?>
                                                    <?php if ($measure == $item["unit_measure"]) { ?>
                                                        <option value="<?= $measure ?>" selected><?= $measure ?></option>
                                                    <?php } ?>
                                                    <option value="<?= $measure ?>"><?= $measure ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Low Stock</label>
                                            <input type="number" pattern="\d" min="0" id="lowstock" name="lowstock" class="form-control" placeholder="Enter quantity where to get notified of low stock" value="<?= $item["low_stock"] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reorder level</label>
                                            <input type="number" pattern="\d" min="0" id="reorder" name="reorder" class="form-control" placeholder="Enter quantity to get notified to reorder" value="<?= $item["reorder"] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Item Description</label>
                                            <input type="text" class="form-control" name="description" id="description" placeholder="Description ..." value="<?= $item["description"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="updateinventory">Update <i class="fa fa-upload" aria-hidden="true"></i></button>
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
    <script src="/assets/js/client/inventory.js"></script>
</body>

</html>