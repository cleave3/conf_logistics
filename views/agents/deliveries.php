<?php

use App\controllers\TaskController;

include_once "common/authheader.php";
$title = "Deliveries";
$currentnav = "deliveries";

$tc = new TaskController();

$tasks = $tc->agentTask();

include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">DELIVERY HISTORY</h4>
                    </div>
                    <div class="card-body responsivetable table-responsive">
                        <div class="card-body responsivetable table-responsive">
                            <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                                <thead role="rowgroup">
                                    <tr role="row">
                                        <th role="columnheader">S/N</th>
                                        <th role="columnheader">SELLER</th>
                                        <th role="columnheader">SELLER&nbsp;TELEPHONE</th>
                                        <th role="columnheader">CUSTOMER</th>
                                        <th role="columnheader">CUSTOMER&nbsp;TELEPHONE</th>
                                        <th role="columnheader">ADDRESS</th>
                                        <th role="columnheader">ORDER&nbsp;ID</th>
                                        <th role="columnheader">STATUS</th>
                                        <th role="columnheader">ASSIGNED&nbsp;AT</th>
                                        <th role="columnheader">UPDATED&nbsp;AT</th>
                                    </tr>
                                </thead>
                                <tbody role="rowgroup">
                                    <?php
                                    $sn = 1;
                                    foreach ($tasks as $task) {
                                    ?>
                                        <tr role="row">
                                            <td role="cell" data-label="SN">
                                                <span><?= $sn ?></span>
                                                <a class="btn btn-sm mx-1 btn-secondary" href="/agents/deliveries/detail?orderid=<?= $task["order_id"] ?>" title="Delivery Details">
                                                    <img src="/assets/icons/details.svg" width="15px" height="15px" />
                                                </a>
                                            </td>
                                            <td role="cell" data-label="SELLER : "><?= $task["seller"] ?></td>
                                            <td role="cell" data-label="SELLER TELEPHONE : "><?= $task["sellertelephone"] ?></td>
                                            <td role="cell" data-label="CUSTOMER : "><?= $task["customer"] ?></td>
                                            <td role="cell" data-label="CUSTOMER TELEPHONE : "><?= $task["customertelephone"] ?></td>
                                            <td role="cell" data-label="ADDRESS : "><?= $task["deliveryaddress"] ?></td>
                                            <td role="cell" data-label="ORDER ID : ">#<?= $task["order_id"] ?></td>
                                            <td data-label="STATUS">
                                                <span class="text-uppercase badge badge-<?= determineClass($task["orderstatus"]) ?> p-2"><?= $task["orderstatus"] ?></span>
                                            </td>
                                            <td role="cell" data-label="CREATED AT : "><?= empty($task["created_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($task["created_at"])) ?></td>
                                            <td role="cell" data-label="UPDATED AT : "><?= empty($task["updated_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($task["updated_at"])) ?></td>
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

    <!-- <script src="/assets/js/client/orders.js"></script> -->
    <script>
        $('#resulttable').DataTable({
            fixedHeader: true,
        });
    </script>
</body>

</html>