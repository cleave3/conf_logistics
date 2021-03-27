<?php

use App\controllers\PackageController;
use App\controllers\PublicController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "New Order";
$currentnav = "orders";
include $base . "common/header.php";
$pc = new PublicController();
$pac = new PackageController();
$items = $pac->getClientPackageItemsWithDetails();
$states = $pc->getStatesForDelivery();
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
                    <li class="breadcrumb-item active">add</li>
                </ol>
                <div class="row">
                    <div class="col-md-7 mx-auto">
                        <div class="card card-user">
                            <div class="card-header">
                                <h6 class="text-center">Complete form to send a new order</h6>
                            </div>
                            <div class="card-body">
                                <form id="registerorderform">
                                    <p>Order Details</p>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" class="form-control" placeholder="Enter customer name" name="customer" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Telephones</label>
                                                <input type="text" class="form-control" placeholder="Enter customer phone numbers" name="telephone" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <select class="custom-select" id="state" name="state" required>
                                                    <option value="" selected disabled>--SELECT STATE--</option>
                                                    <?php foreach ($states as $state) { ?>
                                                        <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <select class="custom-select" name="city" id="city" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" placeholder="Delivery Address..." name="address" required>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Remark/Description/Instructions</label>
                                                <textarea class="form-control" name="description" placeholder="Enter any information" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <section id="order-items" class="">
                                    <form id="orderitemform" class="d-flex w-100 border border-light">
                                        <!-- <div class="col-md-6"> -->
                                        <div class="form-group m-1" style="width: 70%;">
                                            <label>Item</label>
                                            <select id="items" type="text" class="custom-select" name="item" required>
                                                <option selected disabled>--SELECT ITEM--</option>
                                                <?php foreach ($items as $item) { ?>
                                                    <option value="<?= $item["item_id"] ?>"><?= $item["name"] ?></option>
                                                <?php } ?>
                                            </select>
                                            <small id="qtyleft"></small>
                                        </div>
                                        <div class="form-group m-1" style="width: 20%;">
                                            <label>Amount</label>
                                            <input type="text" min="1" id="amount" class="form-control" placeholder="0" value="" name="amount" required>
                                        </div>
                                        <!-- </div> -->
                                        <!-- <div class="col-md-6"> -->
                                        <div class="form-group m-1" style="width: 20%;">
                                            <label>Quantity</label>
                                            <input type="number" min="1" id="quantity" class="form-control" placeholder="quantity" value="1" name="quantity" required>
                                        </div>
                                        <div class="form-group m-1" style="width: 10%;">
                                            <button style="margin-top: 27px;" class="btn btn-sm btn-primary w-100" id="addorderitem"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                        </div>
                                        <!-- </div> -->
                                    </form>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="p-2 card">
                            <h5 class="text-center">Order Summary</h5>
                            <div class="table-responsive" id="order-summary-container">
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary w-50 mx-auto d-none" id="sendorder">Send Order <i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <script>
        formatCurrencyInput(["#amount"])
    </script>
    <script src="/assets/js/client/orders.js"></script>
</body>

</html>