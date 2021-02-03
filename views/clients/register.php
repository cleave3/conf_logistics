<?php

use App\controllers\PublicController;

$pc = new PublicController();
$states =  $pc->states()["data"];
$title = "Client Registration";
include_once "common/header.php";
?>

<body style="background: linear-gradient(#000000b3, #000000b3), url(/assets/img/truck.jpg) no-repeat; background-size:cover;background-position: center;">
    <div id="wrap-parent" class="d-flex justify-content-center align-items-center">

        <div id="wrap-child" class="row bg-white shadow">

            <div class="d-none d-md-block col-md-7 h-100 bg-dark" style="background: linear-gradient(#3a5a58b3, #375f5cb3);">

                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                    <div class="text-center text-white">
                        <i class="nc-icon nc-spaceship" style="font-size: 5rem;"></i>
                        <h1 class="text-center text-white">WELCOME</h1>
                        <p>You are few seconds away completing your registration</p>

                        <div class="mt-5">
                            <h3>Already Have an Account ? </h3>
                            <a href="/clients/login" class="btn btn-outline-success"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Proceed to Login</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-5" style="display: grid; place-items: center">
                <div class="card-body">
                    <h3 class="text-center m-2"><i class="fa fa-user-circle-o"></i> Create Account</h3>
                    <form id="clientregisterform">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">First Name</label>
                                    <input type="text" class="form-control" name="firstname" placeholder="first name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">Last Name</label>
                                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-md-block">Email address</label>
                                    <input type="email" class="form-control" name="email" placeholder="example@mail.com" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-md-block">Telephone</label>
                                    <input type="tel" class="form-control" name="telephone" placeholder="080000000" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-md-block">Company</label>
                                    <input type="text" class="form-control" placeholder="Enter Company Name" name="companyname" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="d-md-block">Address</label>
                                    <input type="text" class="form-control" placeholder="Enter Company Address" name="address" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">State</label>
                                    <select type="text" class="custom-select" name="state" id="state" required>
                                        <?php foreach ($states as $state) { ?>
                                            <option value="<?= $state["state"] ?>"><?= $state["state"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">LGA</label>
                                    <select type="text" class="custom-select" name="city" id="city" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="d-md-block">Confirm Password</label>
                                    <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Enter Password" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="registerbtn">Create Account</button>
                        <small>By proceeding, I agree that I have read, understand and agree with the <a href="/terms">terms &amp; conditions</a>. Also, I have read and understand the relevant <a href="/privacy">Client Privacy Statement</a>.</small>

                        <h6 class="m-3 text-center">Already Have an Account ? <span><a href="/clients/login">Proceed to Login</a></span></h6>

                    </form>
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
    <script src="../assets/js/client/register.js"></script>
</body>

</html>