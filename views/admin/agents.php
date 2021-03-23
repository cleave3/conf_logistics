<?php

use App\controllers\AgentController;

include_once "common/authheader.php";
$title = "Agents";
$currentnav = "agents";
$ac = new AgentController();
$agents = $ac->getAllagents();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>

            <div class="content">
                <a href="/admin/agents/add" class="btn btn-sm btn-success">New Agent <i class="fa fa-book"></i></a>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">AGENTS</h4>
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
                                        <th>ADDRESS</th>
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
                                    foreach ($agents as $agent) {
                                    ?>
                                        <tr>
                                            <td data-label="">
                                                <img class="img-fluid" src="/files/photo/<?= $agent["image"] ? $agent["image"] : "default.jpg" ?>" alt="..." style="width: 40px; height: 40px; cursor:pointer;">
                                            </td>
                                            <td data-label="NAME"><?= $agent["firstname"] ?> <?= $agent["lastname"] ?></td>
                                            <td data-label="TELEPHONE : "><?= $agent["telephone"] ?></td>
                                            <td data-label="EMAIL : "><?= $agent["email"] ?></td>
                                            <td data-label="ADDRESS : "><?= $agent["address"] ?></td>
                                            <td data-label="STATE : "><?= $agent["state"] ?></td>
                                            <td data-label="LGA : "><?= $agent["city"] ?></td>
                                            <td data-label="STATUS">
                                                <span class="text-uppercase badge badge-<?= determineClass($agent["status"]) ?> p-2"><?= $agent["status"] ?></span>
                                            </td>
                                            <td data-label="REG. DATE : "><?= date("Y-m-d H:m:s a", strtotime($agent["created_at"])) ?></td>
                                            <td data-label="ACTIONS " class="d-md-flex justify-content-center">
                                                <a class="btn btn-sm mx-1 btn-primary" href="/admin/agents/edit?agentid=<?= $agent["id"] ?>" title="Edit package">
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
    <!-- <script src="/assets/js/agent/package.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true
        });
    </script>
</body>

</html>