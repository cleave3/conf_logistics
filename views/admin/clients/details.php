<?php

use App\controllers\ClientController;

$base = __DIR__ . "/../";

include_once $base . "common/authheader.php";
$title = "Clients";
$currentnav = "clients";
$cc = new ClientController();
$client = $cc->getClientDetails($_GET["clientid"]);
include_once $base . "common/header.php";
?>

<body class="">
    <div class="wrapper ">
        <?php include $base . "common/sidebar.php" ?>
        <div class="main-panel" style="height: 100vh;">
            <?php include $base . "common/nav.php" ?>
            <div class="content">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="/admin/clients">Clients</a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
                <button class="btn btn-sm btn-primary" onclick="print()"><img src="/assets/icons/printer.svg" width="15px" height="15px" /> Print</button>
                <button onclick="toggleClient('<?= $client['status'] == 'active' ? 'deactivated' : 'active' ?>', '<?= $client['id'] ?>')" class="btn btn-sm btn-<?= $client["status"] == "active" ? "danger" : "success" ?>">
                    <img src="/assets/icons/<?= $client["status"] == "active" ? "forbidden" : "send" ?>.svg" width="15px" height="15px" />
                    <?= $client["status"] == "active" ? "Deactivate Client" : "Activate Client" ?>
                </button>
                <div class="waybill print-container">
                    <div class="card">
                        <div class="card-header">
                            <a href="#">
                                <img id="userphoto" src="/files/photo/<?= $client["image"] ?? "default.jpg" ?>" alt="..." style="width: 200px; height: 200px;">
                            </a>
                            <p class="text-left text-uppercase my-1"><b>Name :</b> <?= $client["firstname"] ?> <?= $client["lastname"] ?></p>
                            <p class="text-left text-uppercase my-1"><b>ID :</b> <?= $client["id"] ?></p>
                        </div>
                        <div class="card-body">
                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="3">CLIENT DATA</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Registration Date : </b> <?= date("Y-m-d H:m:s a", strtotime($client["created_at"])) ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" class="text-uppercase" colspan="2">
                                            <b>Company:</b> <?= strtoupper($client["companyname"]) ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="1">
                                            <b>Telephone : </b> <?= $client["telephone"] ?><br />
                                        </td>

                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="2">
                                            <b>Email : </b> <?= $client["email"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;">
                                            <b>Status : </b><span class="text-uppercase badge badge-<?= determineClass($client["status"]) ?> p-2"><?= $client["status"] ?></span>
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="1">
                                            <b>Profile Completed : </b> <span class="badge badge-<?= determineClass($client["profile_complete"]) ?> p-2"><?= $client["profile_complete"] ?></span>
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="1">
                                            <b>Email Verified : </b> <span class="badge badge-<?= determineClass($client["email_verified"]) ?> p-2"><?= $client["email_verified"] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="1">
                                            <b>State : </b> <?= $client["state"] ?><br />
                                        </td>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="2">
                                            <b>Address : </b> <?= $client["address"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Bio : </b> <?= $client["bio"] ?><br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table style="border-collapse: collapse; width: 100%; border-top: 1.5px solid #DDDDDD; border-left: 1.5px solid #DDDDDD; margin-bottom: 20px;">
                                <thead>
                                    <tr>
                                        <td style="font-size: 12px; border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 15px; color: #222222;" colspan="3">BANK DETAILS</td>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Bank Name : </b> <?= $client["bankname"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Account Numuber : </b> <?= $client["accountnumber"] ?><br />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 12px;	border-right: 1.5px solid #DDDDDD; border-bottom: 1.5px solid #DDDDDD; text-align: left; padding: 15px;" colspan="3">
                                            <b>Account Name : </b> <?= $client["accountname"] ?><br />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once $base . "common/footer.php" ?>
        </div>
    </div>

    <?php include_once $base . "common/js.php" ?>
    <script src="/assets/js/admin/clients.js"></script>
</body>

</html>