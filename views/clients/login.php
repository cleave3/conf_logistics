<?php
session_start();
$title = "Client Login";
$auth = false;

if (isset($_SESSION["clientid"])) {
    $auth = true;
    $user = $_SESSION["username"];
}

include_once "common/header.php";
?>

<body style="background: linear-gradient(#000000b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">

        <div class="row bg-white shadow" style="height: 90%; width: 90%; border-radius: 10px;background-color: #f4f3ef;opacity: 0.85;">
            <div class="col-md-5 w-100" style="display: grid; place-items: center">
                <div class="card-body bg-white">
                    <?php if ($auth) { ?>
                        <div class="d-flex flex-wrap">
                            <a href="/clients/dashboard" class="btn btn-info text-uppercase">CONTINUE AS <?= $user ?> <i class="fa fa-user"></i></a>
                            <a href="/clients/logout" class="btn btn-danger">LOGOUT</a>
                        </div>
                    <?php } ?>
                    <h3 class="text-center m-2"><i class="fa fa-user-circle-o"></i> Welcome Back</h3>
                    <form id="clientloginform">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-none d-md-block">Email</label>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                                        <div class="input-group-append">
                                            <span style="cursor: pointer;" class="input-group-text" id="basic-addon2"><i class="fa fa-envelope text-dark" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-none d-md-block">Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="*****" required>
                                        <div class="input-group-append">
                                            <span style="cursor: pointer;" class="input-group-text" id="basic-addon2"><i id="eye" class="fa fa-eye text-dark" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="loginbtn">Login <img class="ml-1" src="/assets/icons/enter.svg" width="20px" height="20px" /></button>
                        <p class="m-3 text-left">Forgot Password ? <span><a href="/clients/forgotpassword">Click Here</a></span></p>
                        <p class="m-3 text-left">Don't Have an Account ? <span><a href="/clients/register">Register</a></span></p>
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
    <script src="/assets/js/toast.js" type="text/javascript"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/client/login.js"></script>
</body>

</html>