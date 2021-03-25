<?php

use App\controllers\AgentController;
use App\controllers\TaskController;

$base = __DIR__ . "/../";

include_once $base . "common/authheader.php";
$title = "Assign Deliveries";
$currentnav = "tasks";
$tc = new TaskController();
$ac = new AgentController();
$agents = $ac->getActiveAgents();
$tasks = $tc->getAssignableTasks();
include_once $base . "common/header.php";
?>

<div class="wrapper ">
    <?php include $base . "common/sidebar.php" ?>
    <div class="main-panel" style="height: 100vh;">
        <?php include $base . "common/nav.php" ?>
        <div class="content">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/tasks">Delivery Tasks</a>
                </li>
                <li class="breadcrumb-item active">Assign tasks</li>
            </ol>
            <button class="btn btn-sm btn-primary" id="assignbtn" data-toggle="modal" data-target="#assignmentmodal" disabled>Assign <i class="fa fa-book"></i></button>
            <button class="btn btn-sm btn-dark" id="selectall" data-action="select">Select All <i class="fa fa-check"></i></button>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title text-center">ASSIGN DELIVERIES TO AGENTS</h6>
                </div>
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
                                <th role="columnheader">ASSIGNER</th>
                                <th role="columnheader">DELIVERY&nbsp;AGENT</th>
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
                                        <span>
                                            <input type="checkbox" name="items[]" class="items" value="<?= $task["order_id"] ?>">
                                        </span>
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
                                    <td role="cell" data-label="ASSIGNER : "><?= empty($task["assigner"]) ? '<span class="text-uppercase badge badge-warning p-2">unassigned</span>' : $task["assigner"] ?></td>
                                    <td role="cell" data-label="DELIVERY AGENT : "><?= empty($task["assignee"]) ? '<span class="text-uppercase badge badge-warning p-2">unassigned</span>' : $task["assignee"] ?></td>
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

        <!-- Modal -->
        <div class="modal fade" id="assignmentmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="agents">Select Agent to be Assigned</label>
                            <select id="agents" class="custom-select">
                                <option value="">--select Agent--</option>
                                <?php foreach ($agents as $agent) { ?>
                                    <option value="<?= $agent["id"] ?>"><?= $agent["firstname"] ?> <?= $agent["lastname"] ?></option>
                                <?php } ?>
                            </select>
                            <small class="text-danger d-none" id="error-div">please select an agent</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center p-2">
                        <button type="button" class="btn btn-secondary m-1" style="width: 45%;" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary m-1" style="width: 45%;" id="submitbtn">Submit <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once $base . "common/footer.php" ?>
    </div>
</div>

<?php include_once $base . "common/js.php" ?>
<script>
    $('#resulttable').DataTable({
        fixedHeader: true,
        paging: false
    });
</script>
<script src="/assets/js/admin/tasks.js"></script>
</body>

</html>