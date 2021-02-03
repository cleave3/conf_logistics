<?php
$title = "Client Login";

include_once "common/header.php";
?>

<body style="background: linear-gradient(#000000b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">

        <div class="row bg-white shadow" style="height: 90%; width: 90%; border-radius: 10px;background-color: #f4f3ef;opacity: 0.85;">
            <div class="col-md-5" style="display: grid; place-items: center">
                <div class="card-body bg-white">
                    <h3 class="text-center m-2"><i class="fa fa-user-circle-o"></i> Welcome Back</h3>
                    <form id="clientloginform">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-none d-md-block">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-none d-md-block">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="*****" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="loginbtn">Login <i class="fa fa-sign-in"></i></button>
                        <h6 class="m-3 text-left">Forgot Password ? <span><a href="/clients/forgotpassword">Click Here</a></span></h6>
                        <h6 class="m-3 text-left">Don't Have an Account ? <span><a href="/clients/register">Register Here</a></span></h6>
                    </form>
                </div>
            </div>

            <div class="d-none d-md-block col-md-7 h-100 bg-dark" style="background: linear-gradient(#3a5a58b3, #375f5cb3);">

                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="text-center text-white">
                        <i class="nc-icon nc-touch-id" style="font-size: 5rem;"></i>
                        <h1 class="text-center text-white">ENTER</h1>

                        <div class="mt-5">
                            <h3>Don't Have an Account ? </h3>
                            <a href="/clients/register" class="btn btn-outline-success"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Create an Account</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/client/login.js"></script>
</body>

</html>