<div class="sidebar" data-color="danger" data-active-color="danger">
    <div class="logo">
        <a href="/clients/dashboard" class="brand simple-text logo-normal">
            <i class="nc-icon nc-spaceship" style="font-size: 1.5rem;"></i> CONFIDEBAT
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="<?= $currentnav == "dashboard" ? "active" : "" ?>">
                <a href="/clients/dashboard">
                    <i class="nc-icon nc-layout-11"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li>
            <li class="<?= $currentnav == "package" ? "active" : "" ?>">
                <a href="/clients/package">
                    <i class="nc-icon nc-delivery-fast"></i>
                    <p>WayBill Package</p>
                </a>
            </li>
            <li class="<?= $currentnav == "orders" ? "active" : "" ?>">
                <a href="/clients/orders">
                    <i class="nc-icon nc-cart-simple"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="<?= $currentnav == "catalog" ? "active" : "" ?>">
                <a href="/clients/catalog">
                    <i class="nc-icon nc-tile-56"></i>
                    <p>Items Catalog</p>
                </a>
            </li>
            <li class="<?= $currentnav == "inventory" ? "active" : "" ?>">
                <a href="/clients/inventory">
                    <i class="nc-icon nc-box"></i>
                    <p>Package Inventory</p>
                </a>
            </li>
            <!-- <li class="<?= $currentnav == "transactions" ? "active" : "" ?>">
                <a href="/clients/transactions">
                    <i class="nc-icon nc-credit-card"></i>
                    <p>Transaction History</p>
                </a>
            </li> -->
            <li class="<?= $currentnav == "profile" ? "active" : "" ?>">
                <a href="/clients/profile">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Profile</p>
                </a>
            </li>
        </ul>
    </div>
</div>