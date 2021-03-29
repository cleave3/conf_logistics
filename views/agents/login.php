<?php
session_start();
$title = "Agent Login";
$auth = false;

if (isset($_SESSION["agentid"])) {
    $auth = true;
    $user = $_SESSION["agentname"];
}

include_once "common/header.php";
?>

<body style="background: linear-gradient(#3155554f, #4f6c73b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="admin-login bg-light">
            <h3 class="text-center">Agent Login</h3>
            <!-- <div class="d-none d-md-flex justify-content-center my-2"> -->
            <!-- <img src="/assets/icons/delivery_agent.svg" width="50%" class="img-fluid"> -->
            <!-- </div> -->
            <?php if ($auth) { ?>
                <div class="d-flex flex-wrap">
                    <a href="/agents/dashboard" class="btn btn-info text-uppercase">CONTINUE AS <?= $user ?> <i class="fa fa-user"></i></a>
                    <a href="/agents/logout" class="btn btn-danger">LOGOUT</a>
                </div>
            <?php } ?>

            <!-- <h3 class="text-center m-2"><i class="nc-icon nc-touch-id" style="font-size: 3rem;"></i></h3> -->
            <form id="agentsloginform">
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
                <button type="submit" class="btn btn-primary w-100" id="loginbtn">Login <img class="ml-1" src="/assets/icons/enter.svg" width="20px" height="20px" /></button>
                <p class="m-3 text-left">Forgot Password ? <span><a href="/agents/forgotpassword">Click Here</a></span></p>
            </form>
        </div>
    </div>
    <script src="/assets/js/toast.js" type="text/javascript"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/agents/login.js"></script>
</body>

</html>