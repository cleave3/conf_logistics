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
            <div role="tablist" aria-multiselectable="true">
                <div class="card-header <?= $currentnav == "inventory" ? "active" : "" ?>" role="tab" id="inventorytab">
                    <a style="text-decoration: none;" class="text-white" data-toggle="collapse" data-parent="#inventorytab" href="#inventorytabcontent" aria-expanded="true" aria-controls="inventorytabcontent">
                        <i class="nc-icon nc-box"></i>
                        <p>Inventory<span class="fa fa-chevron-down mt-2 pull-right text-white" style="font-size: 13px;"></span></p>
                    </a>
                </div>
                <div id="inventorytabcontent" class="collapse" role="tabpanel">
                    <a href="/clients/stockinventory" class="p-1" style="text-decoration: none;">
                        <li class="p-2 ml-4">
                            Items in Stock
                        </li>
                    </a>
                    <a href="/clients/waybillinventory" class="p-1" style="text-decoration: none;">
                        <li class="p-2 ml-4">
                            Way billed Items
                        </li>
                    </a>
                </div>
            </div>
            <li class="<?= $currentnav == "transactions" ? "active" : "" ?>">
                <a href="/clients/transactions">
                    <i class="nc-icon nc-credit-card"></i>
                    <p>Transaction History</p>
                </a>
            </li>
            <li class="<?= $currentnav == "profile" ? "active" : "" ?>">
                <a href="/clients/profile">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>Profile</p>
                </a>
            </li>
        </ul>
    </div>
</div>