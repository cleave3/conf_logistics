<?php

use App\controllers\DashboardController;

include_once "common/authheader.php";
$title = "Client Dashboard";
$currentnav = "dashboard";
$dc = new DashboardController();
$stats = $dc->clientDashboardStats();
include_once "common/header.php";
?>

<body class="">
  <div class="wrapper ">
    <?php include "common/sidebar.php" ?>
    <div class="main-panel" style="height: 100vh;">
      <?php include "common/nav.php" ?>
      <div class="content">
        <h3>DASHBOARD</h3>
        <div class="row">
          <!--payments -->
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-credit-card text-dark"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Balance</p>
                      <p class="card-title">₦ <?= number_format($stats["balance"]) ?>
                      <p>
                      <p class="card-title p-1">
                      <div style="height: 32px;"></div>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="#">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-money-coins text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Verified Payments</p>
                      <p class="card-title">₦ <?= number_format($stats["verifiedpayments"]) ?>
                      <p>
                      <p class="card-title"><?= $stats["verifiedpaymentscount"] ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/payments">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-money-coins text-primary"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Unverified Payments</p>
                      <p class="card-title">₦ <?= number_format($stats["paidpayments"]) ?>
                      <p>
                      <p class="card-title"><?= $stats["paidpaymentscount"] ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/payments">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <!--payments -->
          <!-- Orders -->
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-bag-16 text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Delivered Orders</p>
                      <p class="card-title"><?= number_format($stats["deliveredorders"]) ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/orders">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-cart-simple text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Pending Orders</p>
                      <p class="card-title"><?= number_format($stats["pendingorders"] + $stats["rescheduledorders"] + $stats["intransitorders"] + $stats["onresponseorders"]) ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/orders">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-basket text-danger"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Cancelled Orders</p>
                      <p class="card-title"><?= number_format($stats["cancelledorders"]) ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/orders">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Orders -->
          <!-- Waybills -->
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-delivery-fast text-primary"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Total WayBills</p>
                      <p class="card-title"><?= $stats["totalwaybills"] ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/package">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-bus-front-12 text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Pending WayBills</p>
                      <p class="card-title"><?= ($stats["unsentwaybills"] + $stats["pendingwaybills"] + $stats["intransitwaybills"]) ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/package">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-box-2 text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Recieved WayBills</p>
                      <p class="card-title"><?= $stats["recievedwaybills"] ?>
                      <p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <a class="text-muted" href="/clients/package">See details &rarr;</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Waybills -->

          <!-- Month Orders stats -->
          <div class="col-md-12">
            <div class="card ">
              <div class="card-header ">
                <h5 class="card-title">Monthly Order Stats</h5>
                <p class="card-category"><?= $stats["period"] ?> Monthly performance</p>
              </div>
              <div class="card-body ">
                <canvas id="monthlystats" width="400" height="100"></canvas>
              </div>
            </div>
          </div>
          <!-- Month Orders stats -->

          <!-- Orders stats by stats -->
          <div class="col-md-12">
            <div class="card ">
              <div class="card-header ">
                <h5 class="card-title">Order Stats By State</h5>
              </div>
              <div class="card-body ">
                <canvas id="statestats" width="400" height="100"></canvas>
              </div>
            </div>
          </div>
          <!--Orders stats by stats -->
        </div>
      </div>
      <?php include_once "common/footer.php" ?>
    </div>
  </div>

  <?php include_once "common/js.php" ?>
  <script src="/assets/js/plugins/chartjs.min.js"></script>
  <script src="/assets/js/client/dashboard.js"></script>
</body>

</html>