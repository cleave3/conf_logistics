<?php

use App\controllers\AuthController;

include_once "common/authheader.php";
$title = "Users";
$currentnav = "users";
$ac = new AuthController();
$users = $ac->getAllUsers();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>

            <div class="content">
                <a href="/admin/users/add" class="btn btn-sm btn-success">New User <i class="fa fa-book"></i></a>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">USERS</h4>
                    </div>
                    <div class="card-body">
                        <div class="responsivetable table-responsive">
                            <table id="resulttable" class="table table-sm table-striped table-hover table-inverse" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>NAME</th>
                                        <th>TELEPHONE</th>
                                        <th>EMAIL</th>
                                        <th>ROLE</th>
                                        <th>STATE</th>
                                        <th>LGA</th>
                                        <th>STATUS</th>
                                        <th>REGISTRATION&nbsp;DATE</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sn = 1;
                                    foreach ($users as $user) {
                                    ?>
                                        <tr>
                                            <td data-label="">
                                                <img class="img-fluid" src="/files/photo/<?= $user["image"] ? $user["image"] : "default.jpg" ?>" alt="..." style="width: 40px; height: 40px; cursor:pointer;">
                                            </td>
                                            <td data-label="NAME"><?= $user["firstname"] ?> <?= $user["lastname"] ?></td>
                                            <td data-label="TELEPHONE : "><?= $user["telephone"] ?></td>
                                            <td data-label="EMAIL : "><?= $user["email"] ?></td>
                                            <td data-label="ROLE : "><?= $user["userrole"] ?></td>
                                            <td data-label="STATE : "><?= $user["state"] ?></td>
                                            <td data-label="LGA : "><?= $user["city"] ?></td>
                                            <td class="font-weight-bold <?= determineClass($user["status"]) ?>"><?= strtoupper($user["status"]) ?></td>
                                            <td data-label="REG. DATE : "><?= date("Y-m-d H:m:s a", strtotime($user["created_at"])) ?></td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <a class="btn btn-sm mx-1 btn-primary" href="/admin/users/edit?userid=<?= $user["id"] ?>" title="Edit package">
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
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
    <!-- <script src="/assets/js/user/package.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>