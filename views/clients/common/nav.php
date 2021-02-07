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
                      <li class="nav-item btn-rotate dropdown">
                          <a class="nav-link dropdown-toggle pull-right" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <?= $name ?><i class="nc-icon nc-circle-10"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                              <a class="dropdown-item" href="/clients/profile">Profile</a>
                              <a class="dropdown-item" href="/clients/changepassword">Change Password</a>
                              <a class="dropdown-item" href="/clients/logout">Logout</a>
                          </div>
                      </li>
                  </ul>
              </div>
          </div>
      </nav>
      <!-- End Navbar -->