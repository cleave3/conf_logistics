<?php

use App\controllers\AuthController;
use App\controllers\PublicController;

include_once "common/authheader.php";

$ac = new AuthController();
$user = $ac->profile();
$pc = new PublicController();
$states =  $pc->states()["data"];
$cities = $pc->cityobject($user["state"])["data"];

$title = "Client Profile";
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
                                            <img id="userphoto" src="/files/photo/<?= $user["image"] ?? "default.jpg" ?>" alt="..." style="width: 200px; height: 200px;border-radius:50%;">
                                        </a>
                                        <form id="photoform" class="d-flex justify-content-center flex-column m-2">
                                            <input class="d-none" type="file" accept="image/*" name="image" id="image" required>
                                            <button id="changebtn" class="btn btn-sm btn-info m-1">CHANGE PHOTO</button>
                                            <button id="uploadbtn" type="submit" class="btn btn-sm btn-primary m-1 d-none">UPLOAD PHOTO <i class="fa fa-upload" aria-hidden="true"></i></button>
                                        </form>
                                        <h6 class="title m-2"><?= $user["firstname"] ?> <?= $user["lastname"] ?></h6>
                                        <h6 class="title m-2">Designation : <?= $user["userrole"] ?></h6>
                                    </div>
                                </div>
                                <p class="text-muted text-center"><?= $user["bio"] ?></p>
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
                                                    <input type="email" class="form-control" placeholder="Email" value="<?= $user["email"] ?>" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" placeholder="Firstname" name="firstname" value="<?= $user["firstname"] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" placeholder="Last Name" name="lastname" value="<?= $user["lastname"] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" class="form-control" placeholder="Address" name="address" value="<?= $user["address"] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Telephone</label>
                                                    <input type="text" class="form-control" placeholder="Telepone" name="telephone" value="<?= $user["telephone"] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State</label>
                                                    <select type="text" class="custom-select" name="state" id="state" required>
                                                        <?php foreach ($states as $state) { ?>
                                                            <?php if ($user["state"] == $state["state"]) { ?>
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
                                                            <?php if ($user["city_town"] == $city["city"]) { ?>
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
                                                    <textarea class="form-control textarea" name="bio"><?= $user["bio"] ?></textarea>
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

    <script src="/assets/js/admin/profile.js"></script>
</body>

</html>