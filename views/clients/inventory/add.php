<?php
$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Add Inventory Item";
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
                    <li class="breadcrumb-item active">add</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Register Item</h5>
                        </div>
                        <div class="card-body">
                            <form id="addinventoryform">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Item Name</label>
                                            <input type="text" id="name" class="form-control" placeholder="Enter product name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" pattern="\d" min="1" id="quantity" name="quantity" class="form-control" placeholder="Enter quantity" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unit Cost</label>
                                            <input type="text" class="form-control" name="cost" id="cost" placeholder="Enter Unit Cost">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Unit Measure</label>
                                            <select name="measure" class="custom-select" required>
                                                <option value="">-- SELECT MEASURE --</option>
                                                <option value="CARTONS">PIECES</option>
                                                <option value="CARTONS">CARTONS</option>
                                                <option value="CARTONS">PACKETS</option>
                                                <option value="CARTONS">GALLONS</option>
                                                <option value="CARTONS">CRATES</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Low Stock</label>
                                            <input type="number" pattern="\d" min="0" id="lowstock" name="lowstock" class="form-control" placeholder="Enter quantity where to get notified of low stock" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Reorder level</label>
                                            <input type="number" pattern="\d" min="0" id="reorder" name="reorder" class="form-control" placeholder="Enter quantity to get notified to reorder" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Item Description</label>
                                            <input type="text" class="form-control" name="description" id="description" placeholder="Description ...">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="submitinventory">Submit</button>
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
    <script>
        formatCurrencyInput(["#cost"]);
    </script>
    <script src="/assets/js/client/inventory.js"></script>
</body>

</html>