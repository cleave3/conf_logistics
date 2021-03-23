<div class="sidebar" data-color="danger" data-active-color="danger">
    <div class="logo">
        <a href="/admin/dashboard" class="brand simple-text logo-normal">
            <i class="nc-icon nc-spaceship" style="font-size: 1.5rem;"></i> CONFIDEBAT
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="<?= $currentnav == "dashboard" ? "active" : "" ?>">
                <a href="/admin/dashboard">
                    <i class="nc-icon nc-layout-11"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
            <li class="<?= $currentnav == "orders" ? "active" : "" ?>">
                <a href="/admin/orders">
                    <i class="nc-icon nc-cart-simple"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="<?= $currentnav == "waybills" ? "active" : "" ?>">
                <a href="/admin/waybills">
                    <i class="nc-icon nc-delivery-fast"></i>
                    <p>WayBills</p>
                </a>
            </li>
            <li class="<?= $currentnav == "clients" ? "active" : "" ?>">
                <a href="/admin/clients">
                    <i class="nc-icon nc-satisfied"></i>
                    <p>Clients</p>
                </a>
            </li>
            <li class="<?= $currentnav == "agents" ? "active" : "" ?>">
                <a href="/admin/agents">
                    <i class="nc-icon nc-user-run"></i>
                    <p>Delivery Agents</p>
                </a>
            </li>
            <li class="<?= $currentnav == "users" ? "active" : "" ?>">
                <a href="/admin/users">
                    <i class="nc-icon nc-single-02"></i>
                    <p>Users</p>
                </a>
            </li>
            <li class="<?= $currentnav == "inventory" ? "active" : "" ?>">
                <a href="/admin/inventory">
                    <i class="nc-icon nc-box"></i>
                    <p>Inventory</p>
                </a>
            </li>
            <li class="<?= $currentnav == "tasks" ? "active" : "" ?>">
                <a href="/admin/tasks">
                    <i class="nc-icon nc-bullet-list-67"></i>
                    <p>Delivery Tasks</p>
                </a>
            </li>
            <li class="<?= $currentnav == "payments" ? "active" : "" ?>">
                <a href="/admin/payments">
                    <i class="nc-icon nc-credit-card"></i>
                    <p>Payments</p>
                </a>
            </li>
            <li class="<?= $currentnav == "profile" ? "active" : "" ?>">
                <a href="/admin/profile">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="<?= $currentnav == "settings" ? "active" : "" ?>">
                <a href="/admin/settings">
                    <i class="nc-icon nc-settings-gear-65"></i>
                    <p>Settings</p>
                </a>
            </li>
        </ul>
    </div>
</div>