<?php
$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Edit User";
$currentnav = "users";
include $base . "common/header.php";

use App\controllers\AuthController;
use App\controllers\PublicController;

$pc = new PublicController();
$ac = new AuthController();
$roles = $ac->getAllRoles();
$states =  $pc->states()["data"];
$user = $ac->getuserbyId($_GET["userid"]);
$cities = $pc->cityobject($user["state"])["data"];
$statuses = [["value" => "active", "label" => "User Activated"], ["value" => "deactivated", "label" => "User Deactivated"]];
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/users">Users</a>
                    </li>
                    <li class="breadcrumb-item active"><?= $user["firstname"] ?> <?= $user["lastname"] ?></li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Edit <?= $user["firstname"] ?>'s Data</h5>
                        </div>
                        <div class="card-body">
                            <form id="edituserform">
                                <div class="row">
                                    <div class="col-md-2 mt-2 mb-2">
                                        <a href="#">
                                            <img class="img-fluid" id="userphoto" src="/files/photo/<?= $user["image"] ?? "default.jpg" ?>" alt="..." style=" max-height: 150px;">
                                        </a>
                                    </div>
                                    <div class="col-md-10">
                                        <?= $user["bio"] ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">User Status</label>
                                            <select type="text" class="custom-select" name="status" id="status" required>
                                                <option value="">--SELECT USER STATUS--</option>
                                                <?php foreach ($statuses as $status) { ?>
                                                    <?php if ($status["value"] == $user["status"]) { ?>
                                                        <option value="<?= $status["value"] ?>" selected><?= $status["label"] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $status["value"] ?>"><?= $status["label"] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Role</label>
                                            <select type="text" class="custom-select" name="role" id="role" required>
                                                <option value="">--SELECT USER ROLE--</option>
                                                <?php foreach ($roles as $role) { ?>
                                                    <?php if ($role["id"] == $user["role"]) { ?>
                                                        <option value="<?= $role["id"] ?>" selected><?= $role["role"] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $role["id"] ?>"><?= $role["role"] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" placeholder="Firstname" name="firstname" value="<?= $user["firstname"] ?>" required>
                                            <input type="hidden" name="userid" value="<?= $user["id"] ?>">
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
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" name="email" placeholder="example@mail.com" required value="<?= $user["email"] ?>">
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Telephone</label>
                                            <input type="text" class="form-control" placeholder="Telephone" name="telephone" value="<?= $user["telephone"] ?>">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select type="text" class="custom-select" name="state" id="state" required>
                                                <?php foreach ($states as $state) { ?>
                                                    <?php if ($state["state"] == $user["state"]) { ?>
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
                                                    <?php if ($city["city"] == $user["city"]) { ?>
                                                        <option value="<?= $city["city"] ?>" selected><?= $city["city"] ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?= $city["city"] ?>"><?= $city["city"] ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="savebtn">SAVE CHANGES <img class="ml-1" src="/assets/icons/save.svg" width="20px" height="20px" /></button>
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
    <script src="/assets/js/admin/user.js"></script>
</body>

</html>