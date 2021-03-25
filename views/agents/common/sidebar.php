<div class="sidebar" data-color="danger" data-active-color="danger">
    <div class="logo">
        <a href="/agents/dashboard" class="brand simple-text logo-normal">
            <i class="nc-icon nc-spaceship" style="font-size: 1.5rem;"></i> CONFIDEBAT
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="<?= $currentnav == "dashboard" ? "active" : "" ?>">
                <a href="/agents/dashboard">
                    <i class="nc-icon nc-layout-11"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="<?= $currentnav == "deliveries" ? "active" : "" ?>">
                <a href="/agents/deliveries">
                    <i class="nc-icon nc-bullet-list-67"></i>
                    <p>Deliveries</p>
                </a>
            </li>
            <li class="<?= $currentnav == "profile" ? "active" : "" ?>">
                <a href="/agents/profile">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Profile</p>
                </a>
            </li>
        </ul>
    </div>
</div>