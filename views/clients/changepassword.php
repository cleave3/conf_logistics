<?php
$title = "Change Password";
$currentnav = "cp";
include_once "common/authheader.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include "common/nav.php" ?>
            <div class="content">

                <div class="col-md-12 mx-auto">
                    <div class="card card-user">
                        <div class="card-header">
                            <h5 class="card-title">Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form id="changepasswordform">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" id="currentpassword" class="form-control" placeholder="Current Password" name="currentpassword" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" id="newpassword" name="newpassword" id="newpassword" class="form-control" placeholder="Current Password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Repeat Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="submit" class="btn btn-primary w-50 mx-auto" id="changepasswordbtn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once "common/footer.php" ?>
        </div>
    </div>

    <?php include_once "common/js.php" ?>
    <script src="/assets/js/client/changepassword.js"></script>
</body>

</html>