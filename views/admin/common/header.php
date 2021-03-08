<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?= $title ?>
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <!-- CSS Files -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <link href="/assets/css/fontawesome-all.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/feedback.css" />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link rel="stylesheet" href="/assets/css/datatables.min.css" />
    <link rel="stylesheet" href="/assets/css/toast.css" />
</head>

<?php
function determineClass($status)
{
    switch ($status) {
        case 'onhand':
        case 'pending':
            return "text-warning";
        case 'sent':
            return "text-primary";
        case "recieved":
        case "active";
            return "text-success";
        case "deactivated":
        case "suspended":
            return "text-danger";
        default:
            return "text-dark";
    }
}
?>