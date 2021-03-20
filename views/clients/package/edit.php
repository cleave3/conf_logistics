<?php
$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Client Package";
$currentnav = "package";
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
                        <a href="/clients/package">Packages</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <marquee class="text-warning">Please donot send package on transit. We advice that all package are sent with a registered company</marquee>
                        <div class="card-header">
                            <h5 class="card-title">UPDATE PACKAGE</h5>
                            <h6 class="text-center">Complete form to update package for waybill</h6>
                        </div>
                        <div class="card-body">
                            <form id="registerpackageform">
                                <p>Package Details</p>
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" class="form-control" placeholder="Enter title for package" name="title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Transport Company</label>
                                            <input type="text" class="form-control" placeholder="Enter name of transport company" name="transportcompany" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Driver's Number <small class="text-muted">(optional)</small></label>
                                            <input type="tel" class="form-control" placeholder="080 XXXX XXXXX" name="drivernumber">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Package Weight (kg)</label>
                                            <input type="number" pattern="\d+" class="form-control" placeholder="Enter package weight" name="weight" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Destination</label>
                                            <select class="custom-select" name="destination" required>
                                                <option value="">--SELECT DESTINATION--</option>
                                                <?php foreach ($locations as $location) { ?>
                                                    <option value="<?= $location["id"] ?>"><?= $location["location"] ?></option>
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


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Instructions</label>
                                            <textarea class="form-control" name="instructions" placeholder="Enter any instructions ..."></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Package Image / Invoice / Way Bill, .... (optional)</label>
                                            <input type="file" accept="image/*" class="w-100" name="image">
                                        </div>
                                    </div>
                                </div>
                                <p class="m-0">Package Items</p>
                                <button class="btn btn-sm btn-primary" id="addpackageitem">Add Item</button>
                                <hr />
                                <section id="package-items" class="mt-5">
                                    <div class="row border border-light mt-2" style="position: relative;">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select id="items" type="text" class="custom-select inventory-items" name="item[]" required>
                                                    <option value="">--SELECT ITEM--</option>
                                                    <?php foreach ($inventories as $inventory) { ?>
                                                        <option value="<?= $inventory["id"] ?>"><?= $inventory["name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Unit Cost</label>
                                                <input type="text" class="form-control unit-cost" placeholder="Enter unit cost of item" name="cost[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" min="1" class="form-control qty" placeholder="Enter Item quantity" name="quantity[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="registerpackagebtn">Submit</button>
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
</body>

</html>