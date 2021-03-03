<?php

use App\controllers\PackageController;

$pc = new PackageController();
$package = $pc->getPackage();
exit(json_encode($package));
