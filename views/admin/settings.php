<?php

use App\controllers\ConfigController;
use App\controllers\PublicController;

include_once "common/authheader.php";
$title = "Settings";
$currentnav = "settings";
$cc = new ConfigController();
$pc = new PublicController();
$settings = $cc->getSettings();
$configs = $cc->getConfigs();
$prices = $cc->getDeliveryPricing();
$locations = $cc->getActiveWayBillLocations();
$states =  $pc->states()["data"];
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>

            <div class="content">
                <h4 class="card-title">SETTINGS</h4>
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="nav d-flex d-md-block nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="basic-tab" data-toggle="pill" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Basic Settings</a>
                            <a class="nav-link" id="configuration-tab" data-toggle="pill" href="#configuration" role="tab" aria-controls="configuration" aria-selected="false">Configurations</a>
                            <a class="nav-link" id="permissions-tab" data-toggle="pill" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">Permissions</a>
                            <a class="nav-link" id="pricing-tab" data-toggle="pill" href="#pricing" role="tab" aria-controls="pricing" aria-selected="false">Pricing</a>
                            <a class="nav-link" id="location-tab" data-toggle="pill" href="#location" role="tab" aria-controls="location" aria-selected="false">Waybill locations</a>
                        </div>
                    </div>
                    <div class="col-md-9 col-12" style="overflow-y: auto; height: calc(100vh - 100px)">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                <form id="basicsettingsform">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary m-0" id="savebasics" title="save changes">Save <i class="fa fa-upload" aria-hidden="true"></i></button>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-2 mb-2">
                                            <a href="#">
                                                <img class="img-fluid" id="companylogo" src="/files/photo/<?= $settings["logo"] ?? "camera.svg" ?>" alt="..." style=" max-height: 200px;">
                                            </a>
                                            <div class="form-group my-3">
                                                <label>Company Logo</label>
                                                <input class="d-none" type="file" accept="image/*" name="image" id="image">
                                                <button id="changebtn" class="btn btn-sm btn-info m-1">CHANGE</button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input type="text" class="form-control" placeholder="companyname" name="companyname" value="<?= $settings["companyname"] ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Company Email</label>
                                                <input type="email" class="form-control" placeholder="compan email" name="email" value="<?= $settings["email"] ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="telephone">Company Telephone</label>
                                                <input type="text" class="form-control" placeholder="company telephone" name="telephone" value="<?= $settings["telephone"] ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="slogan">Company Slogan</label>
                                                <input type="text" class="form-control" placeholder="company slogan" name="slogan" value="<?= $settings["slogan"] ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
                                <?php foreach ($configs as $config) { ?>
                                    <form>
                                        <div class="form-group my-3">
                                            <label><?= $config["keyword"] ?></label>
                                            <input type="hidden" name="id" value="<?= $config["id"] ?>">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <?= evaluteFieldType($config["fieldtype"], $config["value"]) ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-sm btn-info m-1">SAVE&nbsp;<i class="fa fa-upload"></i></button>
                                                </div>
                                            </div>
                                            <small><b class="text-danger">*</b> <?= $config["description"] ?></small>
                                        </div>
                                    </form>
                                <?php } ?>
                            </div>
                            <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                                Implement permission settings here
                            </div>
                            <div class="tab-pane fade" id="pricing" role="tabpanel" aria-labelledby="pricing-tab">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary m-1" data-toggle="modal" data-target="#addpricingmodal" title="Add new">Add&nbsp;New&nbsp;<i class="fa fa-book"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table id="pricingtable" class="table table-sm table-hover table-inverse" style="font-size: 13px;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>STATE</th>
                                                <th>CITY</th>
                                                <th>AMOUNT</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="pricingtablebody">
                                            <?php
                                            $sn = 1;
                                            foreach ($prices as $price) {
                                            ?>
                                                <tr>
                                                    <td data-label=""><?= $sn ?></td>
                                                    <td data-label="STATE : "><?= $price["state"] ?></td>
                                                    <td data-label="CITY : "><?= $price["city"] ?></td>
                                                    <td data-label="AMOUNT : "><?= $nairasymbol ?> <?= number_format($price["amount"], 2) ?></td>
                                                    <td data-label=" " class="d-md-flex justify-content-center">
                                                        <a class="btn btn-sm mx-1 btn-primary" href="#" data-toggle="modal" data-target="#editpricingmodal" title="Edit Pricing" onclick="setEditDetails('pricing' , '<?= $price['id'] ?>')">
                                                            <img src="/assets/icons/edit.svg" width="20px" height="20px" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $sn++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary m-1" data-toggle="modal" data-target="#addlocationmodal" title="save changes">Add&nbsp;<i class="fa fa-book"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table id="locationtable" class="table table-sm table-hover table-inverse" style="font-size: 13px;">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>STATE</th>
                                                <th>LOCATION</th>
                                                <th>AMOUNT</th>
                                                <th>STATUS</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="locationtablebody">
                                            <?php
                                            $sn = 1;
                                            foreach ($locations as $location) {
                                            ?>
                                                <tr>
                                                    <td data-label=""><?= $sn ?></td>
                                                    <td data-label="STATE : "><?= $location["state"] ?></td>
                                                    <td data-label="LOCATION : "><?= $location["location"] ?></td>
                                                    <td data-label="AMOUNT : "><?= $nairasymbol ?> <?= number_format($location["amount"], 2) ?></td>
                                                    <td data-label="STATUS : " class="<?= determineClass($location["status"]) ?>"><?= $location["status"] ?></td>
                                                    <td data-label=" " class="d-md-flex justify-content-center">
                                                        <a class="btn btn-sm mx-1 btn-primary" href="#" data-toggle="modal" data-target="#editlocationmodal" title="Edit Pricing" onclick="setEditDetails('location' , '<?= $location['id'] ?>')" title="Edit Location">
                                                            <img src="/assets/icons/edit.svg" width="20px" height="20px" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $sn++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>
    <div class="modal-section">
        <!--New Pricing Modal -->
        <div class="modal fade" id="addpricingmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit to add a new region</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="pricingform">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select type="text" class="custom-select" name="state" id="state" required>
                                            <?php foreach ($states as $state) { ?>
                                                <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>City/Town</label>
                                        <input type="text" class="form-control" placeholder="City/Town" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="pricing_amount" placeholder="000,000" name="amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary w-100 mx-auto" id="addpricingbtn">SUBMIT <i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--New Pricing Modal -->

        <!--Edit Pricing Modal -->
        <div class="modal fade" id="editpricingmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit region</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editpricingform">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select type="text" class="custom-select" name="state" id="price_state" required>
                                            <?php foreach ($states as $state) { ?>
                                                <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>City/Town</label>
                                        <input type="text" class="form-control" placeholder="City/Town" id="price_city" name="city" required>
                                        <input type="hidden" name="priceid" id="price_id">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="price_amount" placeholder="000,000" name="amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary w-100 mx-auto" id="editpricingbtn">SUBMIT <i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Edit Pricing Modal -->

        <!--New Location Modal -->
        <div class="modal fade" id="addlocationmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Submit to add a new waybill location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="locationform">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select type="text" class="custom-select" name="state" id="state" required>
                                            <?php foreach ($states as $state) { ?>
                                                <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" class="form-control" placeholder="Location name" name="location" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="l_amount" placeholder="000,000" name="amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary w-100 mx-auto" id="addlocationbtn">SUBMIT <i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--New Location Modal -->

        <!--Edit Location Modal -->
        <div class="modal fade" id="editlocationmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit waybill location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editlocationform">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>State</label>
                                        <select type="text" class="custom-select" name="state" id="location_state" required>
                                            <?php foreach ($states as $state) { ?>
                                                <option value="<?= $state["id"] ?>"><?= $state["state"] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" class="form-control" placeholder="Location name" id="location_location" name="location" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" class="form-control" id="location_amount" placeholder="000,000" name="amount" required>
                                        <input type="hidden" id="location_id" name="locationid">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="custom-select" id="location_status" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="submit" class="btn btn-primary w-100 mx-auto" id="editlocationbtn">SUBMIT <i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Edit Location Modal -->
    </div>

    <?php include_once "common/js.php" ?>
    <script src="/assets/js/admin/settings.js"></script>
    <script>
        $('#pricingtable').DataTable({
            fixedHeader: true
        });
        $('#locationtable').DataTable({
            fixedHeader: true
        });
        formatCurrencyInput(["#pricing_amount", "#l_amount", "#location_amount", "#price_amount"])
    </script>
</body>

</html>