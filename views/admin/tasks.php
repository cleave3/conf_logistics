<?php

include_once "common/authheader.php";
$title = "Deliveries";
$currentnav = "tasks";

use App\controllers\AgentController;

$ac = new AgentController();
$agents = $ac->getAllAgents();

$statuses = [
    ["value" => "all", "label" => ucwords("all")],
    ["value" => "cancelled", "label" => ucwords("cancelled")],
    ["value" => "confirmed", "label" => ucwords("confirmed")],
    ["value" => "delivered", "label" => ucwords("delivered")],
    ["value" => "intransit", "label" => ucwords("intransit")],
    ["value" => "processing", "label" => ucwords("Processing")],
    ["value" => "noresponse", "label" => ucwords("noresponse")],
    ["value" => "sent", "label" => ucwords("sent")],
    ["value" => "rescheduled", "label" => ucwords("rescheduled")],
];
include_once "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">
                <a href="/admin/tasks/assign" class="btn btn-sm btn-success">Assign Delivery <i class="fa fa-paper-plane"></i></a>
                <h6 class="text-center">Search Deliveries</h6>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="agents">Select Agent</label>
                            <select id="agents" class="custom-select">
                                <option value="all">All</option>
                                <?php foreach ($agents as $agent) { ?>
                                    <option value="<?= $agent["id"] ?>"><?= $agent["firstname"] ?> <?= $agent["lastname"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" class="custom-select">
                                <?php foreach ($statuses as $status) { ?>
                                    <option value="<?= $status["value"] ?>"><?= $status["label"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="startdate">Start Date</label>
                            <input type="date" id="startdate" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="enddate">End Date</label>
                            <input type="date" id="enddate" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="btn btn-primary mx-0 w-25" id="searchbtn">search <i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
                <div id="result-container" class="responsivetable table-responsive"></div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>

    <script src="/assets/js/admin/tasks.js"></script>
</body>

</html>