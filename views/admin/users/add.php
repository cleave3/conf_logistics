<?php
$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$title = "Register User";
$currentnav = "users";
include $base . "common/header.php";

use App\controllers\AuthController;
use App\controllers\PublicController;

$pc = new PublicController();
$ac = new AuthController();
$roles = $ac->getAllRoles();
$states =  $pc->states()["data"];
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/clients/stockinventory">Users</a>
                    </li>
                    <li class="breadcrumb-item active">add</li>
                </ol>

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Register New User</h5>
                        </div>
                        <div class="card-body">
                            <form id="registeruserform">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control" placeholder="Firstname" name="firstname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control" placeholder="Last Name" name="lastname">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address</label>
                                            <input type="email" class="form-control" name="email" placeholder="example@mail.com" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Role</label>
                                            <select type="text" class="custom-select" name="role" id="role" required>
                                                <option value="">--SELECT USER ROLE--</option>
                                                <?php foreach ($roles as $role) { ?>
                                                    <option value="<?= $role["id"] ?>"><?= $role["role"] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" placeholder="Address" name="address">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Telephone</label>
                                            <input type="text" class="form-control" placeholder="Telepone" name="telephone">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select type="text" class="custom-select" name="state" id="state" required>
                                                <?php foreach ($states as $state) { ?>
                                                    <option value="<?= $state["state"] ?>"><?= $state["state"] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>LGA</label>
                                            <select type="text" class="custom-select" name="city" id="city" required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Bio</label>
                                            <textarea class="form-control textarea" name="bio"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="registerbtn">CREATE USER <img class="ml-1" src="/assets/icons/add-user.svg" width="20px" height="20px" /></button>
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
    <script src="/assets/js/admin/register.js"></script>
</body>

</html>