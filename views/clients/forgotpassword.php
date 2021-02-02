<?php
$title = "Forgot Password";

include_once "common/header.php";
?>

<body style="background: linear-gradient(#000000b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">

        <div class="row bg-white shadow" style="height: 90%; width: 90%; border-radius: 10px;background-color: #f4f3ef;opacity: 0.85;">

            <div class="col-md-12 h-100 bg-dark" style="background: linear-gradient(#3a5a58b3, #375f5cb3);">

                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="text-center text-white">
                        <i class="fa fa-lock" style="font-size: 5rem;"></i>
                        <h1 class="text-center text-white">Forgot Password</h1>
                        <form id="forgotpasswordform" class=" w-100">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100" id="forgotpasswordbtn">Submit <i class="fa fa-paper-plane"></i></button>
                            <h6 class="m-3 text-center"><span><a href="/clients/login">Back to login</a></span></h6>
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
    <script src="/assets/js/plugins/bootstrap-notify.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/client/forgotpassword.js"></script>
</body>

</html>