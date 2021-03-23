<?php

use App\utils\Session;

Session::start();
$email = "";
if (isset($_SESSION["ce"])) {
    $email = base64_decode($_SESSION["ce"]);
} else if (isset($_GET["ce"])) {
    $email = base64_decode($_GET["ce"]);
}

$title = "Reset Password";

include_once "common/header.php";
?>

<body style="background: linear-gradient(#000000b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">

        <div class="row bg-white shadow" style="height: 90%; width: 90%; border-radius: 10px;background-color: #f4f3ef;opacity: 0.85;">

            <div class="col-md-12 h-100 bg-dark" style="background: linear-gradient(#3a5a58b3, #375f5cb3);">

                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="text-white">
                        <i class="fa fa-lock" style="font-size: 5rem;"></i>
                        <h1 class="text-center text-white">Reset Password</h1>
                        <form id="resetpasswordform" class=" w-100">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" class="form-control" name="email" placeholder="Enter Email" value="<?= $email ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-left">Enter Token</label>
                                        <input type="text" class="form-control" name="token" placeholder="Enter Token" value="CONF-" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-left">Password</label>
                                        <input type="password" id="password" class="form-control" name="password" placeholder="password" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-left">Confirm Password</label>
                                        <input type="password" id="cpassword" class="form-control" name="cpassword" placeholder="confirm  password" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100" id="resetpasswordbtn">Submit <i class="fa fa-paper-plane"></i></button>
                            <h6 class="m-3 text-center"><span><a href="/agents/login">Back to login</a></span></h6>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="/assets/js/core/jquery.min.js"></script>
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="/assets/js/toast.js" type="text/javascript"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/agents/resetpassword.js"></script>
</body>

</html>