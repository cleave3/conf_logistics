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

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">ADD PACKAGE</h5>
                            <h6 class="text-center">Complete form to register package for waybill</h6>
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
                                            <label>Package Weight (kg)</label>
                                            <input type="text" pattern="\d+" class="form-control" placeholder="Enter package weight" name="weight" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Destination</label>
                                            <select class="custom-select" name="destination" required>
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
                                </div>
                                <p class="m-0">Package Items</p>
                                <button class="btn btn-sm btn-primary" id="addpackageitem">Add package</button>
                                <hr />
                                <section id="package-items" class="mt-5">
                                    <div class="row border border-light mt-2" style="position: relative;">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Item</label>
                                                <select type="text" class="custom-select" name="item[]" required>
                                                    <option value="">--SELECT ITEM--</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Unit Cost</label>
                                                <input type="text" class="form-control" placeholder="Enter unit cost of item" name="cost[]" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" min="1" class="form-control" placeholder="Enter Item quantity" name="quantity[]" required>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="changepasswordbtn">Submit</button>
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
    <script src="/assets/js/client/package.js"></script>
</body>

</html>