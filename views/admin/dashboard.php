<?php

use App\controllers\DashboardController;

include_once "common/authheader.php";
$title = "Admin Dashboard";
$currentnav = "dashboard";
$dc = new DashboardController();
$stats = $dc->DashboardStats();
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <h3>DASHBOARD</h3>
                <div class="row">
                    <!--payments -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-money-coins text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Verified Payments</p>
                                            <p class="card-title">₦ <?= number_format($stats["verifiedpayments"], 2) ?>
                                            <p>
                                            <p class="card-title"><?= $stats["verifiedpaymentscount"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/payments">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-money-coins text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Unverified Payments</p>
                                            <p class="card-title">₦ <?= number_format($stats["paidpayments"], 2) ?>
                                            <p>
                                            <p class="card-title"><?= $stats["paidpaymentscount"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/payments">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-money-coins text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Unpaid Payments</p>
                                            <p class="card-title">₦ <?= number_format($stats["unpaidpayments"], 2) ?>
                                            <p>
                                            <p class="card-title"><?= $stats["unpaidpaymentscount"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/payments">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--payments -->
                    <!-- Orders -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-bag-16 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Delivered Orders</p>
                                            <p class="card-title"><?= number_format($stats["deliveredorders"]) ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/orders?type=delivered">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-cart-simple text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Pending Orders</p>
                                            <p class="card-title"><?= number_format($stats["pendingorders"] + $stats["rescheduledorders"] + $stats["intransitorders"] + $stats["onresponseorders"]) ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/orders?type=pending">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-basket text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Cancelled Orders</p>
                                            <p class="card-title"><?= number_format($stats["cancelledorders"]) ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/orders?type=cancelled">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders -->
                    <!-- Waybills -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-delivery-fast text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Total WayBills</p>
                                            <p class="card-title"><?= $stats["totalwaybills"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/waybills">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-bus-front-12 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Pending WayBills</p>
                                            <p class="card-title"><?= ($stats["unsentwaybills"] + $stats["pendingwaybills"] + $stats["intransitwaybills"]) ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/waybills">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-box-2 text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Recieved WayBills</p>
                                            <p class="card-title"><?= $stats["recievedwaybills"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/waybills">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Waybills -->
                    <!-- Deliveries -->
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-warning">
                                            <i class="nc-icon nc-delivery-fast text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Total Deliveries</p>
                                            <p class="card-title"><?= $stats["total"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/tasks">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-success">
                                            <i class="nc-icon nc-user-run text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Completed Deliveries</p>
                                            <p class="card-title"><?= $stats["totalcompleted"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/tasks">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-5 col-md-4">
                                        <div class="icon-big text-center icon-danger">
                                            <i class="nc-icon nc-box-2 text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-md-8">
                                        <div class="numbers">
                                            <p class="card-category">Uncompleted Deliveries</p>
                                            <p class="card-title"><?= $stats["totaluncompleted"] ?>
                                            <p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ">
                                <hr>
                                <div class="stats">
                                    <a class="text-muted" href="/admin/tasks">See details &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Deliveries -->

                    <!-- Month Orders stats -->
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header ">
                                <h5 class="card-title">Monthly Order Stats</h5>
                                <p class="card-category"><?= $stats["period"] ?> Monthly performance</p>
                            </div>
                            <div class="card-body ">
                                <canvas id="monthlystats" width="400" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Month Orders stats -->

                    <!-- Orders stats by stats -->
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header ">
                                <h5 class="card-title">Order Stats By State</h5>
                                <p class="card-category">Order classification by states</p>
                            </div>
                            <div class="card-body ">
                                <canvas id="statestats" width="400" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--Orders stats by stats -->

                    <!-- Orders stats by stats -->
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header ">
                                <h5 class="card-title">Delivery Stats</h5>
                                <p class="card-category">Delivery stats by agents</p>
                            </div>
                            <div class="card-body ">
                                <canvas id="deliverystats" width="400" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <!--Orders stats by stats -->

                    <!-- Uncompleted deliveries -->
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-header ">
                                <h5 class="card-title">Pending Deliveries</h5>
                                <!-- <p class="card-category">All Time</p> -->
                            </div>
                            <div class="card-body ">
                                <div class="card-body responsivetable table-responsive">
                                    <table role="table" id="resulttable" class="table table-sm table-striped table-hover" style="font-size: 13px;">
                                        <thead role="rowgroup">
                                            <tr role="row">
                                                <th role="columnheader">S/N</th>
                                                <th role="columnheader">CUSTOMER</th>
                                                <th role="columnheader">CUSTOMER&nbsp;TELEPHONE</th>
                                                <th role="columnheader">ADDRESS</th>
                                                <th role="columnheader">ORDER&nbsp;ID</th>
                                                <th role="columnheader">STATUS</th>
                                                <th role="columnheader">DELIVERY&nbsp;AGENT</th>
                                                <th role="columnheader">ASSIGNER</th>
                                                <th role="columnheader">ASSIGNED&nbsp;AT</th>
                                            </tr>
                                        </thead>
                                        <tbody role="rowgroup">
                                            <?php
                                            $sn = 1;
                                            foreach ($stats["uncompleted"] as $task) {
                                            ?>
                                                <tr role="row">
                                                    <td role="cell" data-label="SN">
                                                        <span><?= $sn ?></span>
                                                    </td>
                                                    <td role="cell" data-label="CUSTOMER : "><?= $task["customer"] ?></td>
                                                    <td role="cell" data-label="CUSTOMER TELEPHONE : "><?= $task["customertelephone"] ?></td>
                                                    <td role="cell" data-label="ADDRESS : "><?= $task["deliveryaddress"] ?></td>
                                                    <td role="cell" data-label="ORDER ID : ">#<?= $task["order_id"] ?></td>
                                                    <td data-label="STATUS">
                                                        <span class="text-uppercase badge badge-<?= determineClass($task["orderstatus"]) ?> p-2"><?= $task["orderstatus"] ?></span>
                                                    </td>
                                                    <td role="cell" data-label="DELIVERY AGENT : "><?= empty($task["assignee"]) ? '<span class="text-uppercase badge badge-warning p-2">unassigned</span>' : $task["assignee"] ?></td>
                                                    <td role="cell" data-label="ASSIGNER : "><?= empty($task["assigner"]) ? '<span class="text-uppercase badge badge-warning p-2">unassigned</span>' : $task["assigner"] ?></td>
                                                    <td role="cell" data-label="CREATED AT : "><?= empty($task["created_at"]) ? "never" : date("Y-m-d, H:m:s a", strtotime($task["created_at"])) ?></td>
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
                    <!-- Uncompleted deliveries -->
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
    <script src="/assets/js/plugins/chartjs.min.js"></script>
    <script src="/assets/js/admin/dashboard.js"></script>
</body>

</html>