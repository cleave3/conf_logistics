      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
          <div class="container-fluid">
              <div class="navbar-wrapper">
                  <div class="navbar-toggle">
                      <button type="button" class="navbar-toggler">
                          <span class="navbar-toggler-bar bar1"></span>
                          <span class="navbar-toggler-bar bar2"></span>
                          <span class="navbar-toggler-bar bar3"></span>
                      </button>
                  </div>
                  <a class="navbar-brand text-uppercase font-weight-bold" href="dashboard"><?= $companyname ?></a>
              </div>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
              </button>
              <div class="collapse navbar-collapse justify-content-end" id="navigation">
                  <ul class="navbar-nav">
                      <li class="nav-item btn-rotate dropdown" style="position: relative;">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="nc-icon nc-bell-55" style="font-size: 1.5rem;"></i>
                          </a>
                          <?php

                            use App\controllers\DashboardController;

                            $dc = new DashboardController();
                            $pending = $dc->pendingdeliveries();
                            ?>
                          <?php if (count($pending) > 0) { ?>
                              <span class="text-white badge-pill badge-danger px-1 notification-indicator"><?= count($pending) ?></span>
                              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                  <?php foreach ($pending as $p) { ?>
                                      <a class="dropdown-item" href="/agents/deliveries/detail?orderid=<?= $p["order_id"] ?>">
                                          New Delivery with Order ID #<?= $p["order_id"] ?>
                                      </a>
                                      <hr />
                                  <?php } ?>
                              </div>
                          <?php } ?>
                      </li>
                      <li class="nav-item btn-rotate dropdown">
                          <a class="nav-link dropdown-toggle pull-right" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <?= $name ?><img class="img-fluid mx-2" src="/files/photo/<?= $image ?? "default.jpg" ?>" alt="..." style="width: 40px; height: 40px; cursor:pointer;border-radius: 50%">
                          </a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                              <a class="dropdown-item" href="/agents/profile">Profile</a>
                              <a class="dropdown-item" href="/agents/changepassword">Change Password</a>
                              <a class="dropdown-item" href="/agents/logout">Logout</a>
                          </div>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
      <!-- End Navbar -->