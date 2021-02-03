<div class="sidebar" data-color="danger" data-active-color="danger">
    <div class="logo">
        <a href="dashboard" class="brand simple-text logo-normal">
            <i class="nc-icon nc-spaceship" style="font-size: 1.5rem;"></i> CONFIDEBAT
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="<?= $currentnav == "dashboard" ? "active" : "" ?>">
                <a href="dashboard">
                    <i class="nc-icon nc-layout-11"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
            <li class="<?= $currentnav == "package" ? "active" : "" ?>">
                <a href="package">
                    <i class="nc-icon nc-bank"></i>
                    <p>Register Package</p>
                </a>
            </li>
            <li class="<?= $currentnav == "waybill" ? "active" : "" ?>">
                <a href="waybill">
                    <i class="nc-icon nc-delivery-fast"></i>
                    <p>WayBill</p>
                </a>
            </li>
            <li class="<?= $currentnav == "order" ? "active" : "" ?>">
                <a href="order">
                    <i class="nc-icon nc-cart-simple"></i>
                    <p>Send Order</p>
                </a>
            </li>
            <li class="<?= $currentnav == "inventory" ? "active" : "" ?>">
                <a href="inventory">
                    <i class="nc-icon nc-box"></i>
                    <p>Inventory</p>
                </a>
            </li>
            <li class="<?= $currentnav == "transactions" ? "active" : "" ?>">
                <a href="transactions">
                    <i class="nc-icon nc-credit-card"></i>
                    <p>Transaction History</p>
                </a>
            </li>
            <li class="<?= $currentnav == "profile" ? "active" : "" ?>">
                <a href="profile">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Profile</p>
                </a>
            </li>
        </ul>
    </div>
</div>