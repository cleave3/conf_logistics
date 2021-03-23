<?php

use App\controllers\AgentController;
use App\controllers\PublicController;

include_once "common/authheader.php";

$ac = new AgentController();
$pc = new PublicController();
$data = $ac->profile();
$banks =  $pc->getBankList()["data"];
$states =  $pc->states()["data"];
$cities = $pc->cityobject($data["state"]);

$title = "Agent Profile";
$currentnav = "profile";

include_once "common/header.php";
?>


<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <div class="content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="d-flex justify-content-center p-2">
                                    <div>
                                        <a href="#">
                                            <img id="agentphoto" src="/files/photo/<?= $data["image"] ?? "default.jpg" ?>" alt="..." style="width: 200px; height: 200px;border-radius:50%;">
                                        </a>
                                        <form id="photoform" class="d-flex justify-content-center flex-column m-2">
                                            <input class="d-none" type="file" accept="image/*" name="image" id="image" required>
                                            <button id="changebtn" class="btn btn-sm btn-info m-1">CHANGE PHOTO</button>
                                            <button id="uploadbtn" type="submit" class="btn btn-sm btn-primary m-1 d-none">UPLOAD PHOTO <i class="fa fa-upload" aria-hidden="true"></i></button>
                                        </form>
                                        <h6 class="text-center title my-2"><?= $data["firstname"] ?> <?= $data["lastname"] ?></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Bank Details</h5>
                                </div>
                                <div class="card-body">
                                    <form id="accountdetailform">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Selecy Bank</label>
                                                    <select name="bankcode" class="custom-select" required>
                                                        <option value="">--SELECT BANK--</option>
                                                        <?php foreach ($banks as $bank => $value) { ?>
                                                            <?php if ($data["bankcode"] === $value["bankcode"]) { ?>
                                                                <option value="<?= $value["bankcode"] ?>" selected><?= $value["bankname"] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $value["bankcode"] ?>"><?= $value["bankname"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="accountnumber">Account Number</label>
                                                    <input type="text" class="form-control" pattern="\d{10}" placeholder="accountnumber" name="accountnumber" value="<?= $data["accountnumber"] ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="accountname">Account Name</label>
                                                    <input type="text" class="form-control" placeholder="account name" name="accountname" value="<?= $data["accountname"] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary w-50 mx-auto" id="submitbankdetails">Submit <i class="fa fa-paper-plane"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card card-user">
                                <div class="card-header">
                                    <h5 class="card-title">Edit Profile</h5>
                                </div>
                                <div class="card-body">

                                    <form id="editprofileform">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Email address</label>
                                                    <input type="email" class="form-control" placeholder="Email" value="<?= $data["email"] ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" placeholder="Firstname" name="firstname" value="<?= $data["firstname"] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" placeholder="Last Name" name="lastname" value="<?= $data["lastname"] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" class="form-control" placeholder="Address" name="address" value="<?= $data["address"] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Telephone</label>
                                                    <input type="text" class="form-control" placeholder="Telepone" name="telephone" value="<?= $data["telephone"] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <select type="text" class="custom-select" name="state" id="state" required>
                                                        <?php foreach ($states as $state) { ?>
                                                            <?php if ($data["state"] == $state["state"]) { ?>
                                                                <option value="<?= $state["state"] ?>" selected><?= $state["state"] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $state["state"] ?>"><?= $state["state"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>LGA</label>
                                                    <select type="text" class="custom-select" name="city" id="city" required>
                                                        <?php foreach ($cities as $city) { ?>
                                                            <?php if ($data["city"] == $city["city"]) { ?>
                                                                <option value="<?= $city["city"] ?>" selected><?= $city["city"] ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?= $city["city"] ?>"><?= $city["city"] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>About Me</label>
                                                    <textarea class="form-control textarea" name="bio"><?= $data["bio"] ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary w-50 mx-auto" id="updateprofilebtn">Update Profile <i class="fa fa-upload" aria-hidden="true"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>

    <script src="/assets/js/agents/profile.js"></script>
</body>

</html>