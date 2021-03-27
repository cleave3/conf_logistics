<?php

use App\controllers\OrderController;
use App\controllers\TaskController;

$base = __DIR__ . "/../";
include $base . "common/authheader.php";
$oc = new OrderController();
$order = $oc->getOrderDetails($_GET["orderid"]);
$task = new TaskController();
$title = "Delivery Payment";
$currentnav = "deliveries";

include $base . "common/header.php";
?>

<body class="">
    <div class="wrapper">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <?php if ($order["order"]) { ?>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/agents/deliveries">Deliveries</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="/agents/deliveries/detail?orderid=<?= $_GET["orderid"] ?>">Detail</a>
                        </li>
                        <li class="breadcrumb-item active">Payment</li>
                    </ol>
                    <?php if ($task->getTaskByOrderId($order["order"]["id"])["sendpayment"] === "YES") { ?>
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-center">PAYMENT HAS ALREADY BEEN SUBMITTED FOR THIS DELIVERY <i class="fa fa-credit-card" aria-hidden="true"></i></h6>
                                <div class="d-flex justify-content-center">
                                    <a class="my-2 btn btn-dark" href="/agents/deliveries"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 250px);">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-center">SUBMIT PAYMENT <i class="fa fa-credit-card" aria-hidden="true"></i></h6>
                                    <form id="paymentform" autocomplete="off">
                                        <input type="hidden" name="orderid" value="<?= $_GET["orderid"] ?>" />
                                        <div class="form-group">
                                            <label for="paymentoption">select payment option</label>
                                            <select name="paymentoption" id="paymentoption" class="custom-select">
                                                <option value="card" selected>Card Payment</option>
                                                <option value="bank">Bank Payment/Mobile transfer</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="text" class="form-control" id="amount" placeholder="Enter Amount" value="<?= $order["order"]["totalamount"] ?>" readonly />
                                        </div>
                                        <div class="form-submit-card">
                                            <div class="form-group">
                                                <label for="email">Email <small class="text-muted"></small></label>
                                                <input type="email" id="email" class="form-control" placeholder="example@mail.com">
                                                <small class="text-danger d-none">please enter a valid email address</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="first-name">First Name</label>
                                                <input type="text" class="form-control" id="first-name" placeholder="First Name" /><small class="text-danger d-none">firstname is required</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="last-name">Last Name</label>
                                                <input type="text" class="form-control" id="last-name" placeholder="Last Name" /><small class="text-danger d-none">lastname is required</small>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success w-100 text-white" id="paymentbtn">Proceed&nbsp;to&nbsp;payment&nbsp;<i class="fa fa-chevron-right"></i></button>
                                        </div>
                                        <div class="form-submit-bank d-none">
                                            <div class="form-group">
                                                <label for="proof">Proof of payment <small class="text-danger">(only images and pdf are allowed)</small> </label>
                                                <input type="file" id="proof" accept="image/*,.pdf" class="form-control" name="proof" required />
                                                <small class="text-danger d-none">proof is required</small>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success w-100 text-white" id="submitpayment">Submit&nbsp;payment&nbsp;<i class="fa fa-chevron-right"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="d-flex justify-content-center align-items-center my-5" style="height: 300px;">
                        <div>
                            <p class="text-center font-weight-bold">Delivery not found</p>
                            <img src="/assets/icons/empty.svg" class="img-fluid" width="200px" height="200px" />
                            <div class="d-flex justify-content-center">
                                <a class="my-2 btn btn-dark" href="/agents/deliveries"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php include $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include $base . "common/js.php" ?>
    <?php if ($order["order"] && $task->getTaskByOrderId($order["order"]["id"])["sendpayment"] !== "YES") { ?>
        <script src="/assets/js/plugins/psinline.js"></script>
        <script src="/assets/js/agents/payment.js"></script>
    <?php } ?>
</body>

</html>