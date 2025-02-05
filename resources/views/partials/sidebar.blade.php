<?php
if (!empty($_SESSION['lang'])) {
    $sessionLang = $_SESSION['lang'];
    require_once 'assets/lang-php/' . $sessionLang . '.php';
} else {
    require_once 'assets/lang-php/en.php';
}
?>
<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu"><?php echo $lang['Menu']; ?></li>

                <li>
                    <a href="home" class="">
                        <i class="bx bx-home"></i> <!-- Home Icon -->
                        <span key="t-dashboards"><?php echo $lang['Dashboard']; ?></span>
                    </a>
                </li>

                {{-- @if (auth()->user()->role != 2 && auth()->user()->role != 3) --}}
                    @can('view-company')
                        <li><a href="{{ route('supplier.index') }}" key="t-brand"><i class="bx bx-buildings"></i><span
                                    key="t-brands">
                                    Suppliers</span></a></li> <!-- Building Icon -->
                    @endcan
                    @can('view-organization')
                    <li><a href="{{ route('organization.index') }}" key="t-role-manager"><i class="bx bx-buildings"></i>
                            <span key="t-organization"> Organization</span></a></li> <!-- Building Icon -->
                    @endcan
                    @can('view-user-management')
                        <li>
                            <a href="{{ route('user.management') }}" class="">
                                <i class="bx bx-user"></i> <!-- User Icon -->
                                <span key="t-dashboards">User Management</span>
                            </a>
                        </li>
                    @endcan
                    @can('view-customer')
                    <li><a href="{{ route('customer.index') }}" class=""><i
                                class="bx bx-add-to-queue"></i>Customer</a></li>
                    @endcan

                    @can('view-product')
                        <li><a href="{{ route('product.index') }}" key="t-role-manager"><i class="bx bx-box"></i>
                                <span key="t-products">Product</span></a></li> <!-- Box Icon -->
                    @endcan
                    @can('view-sell')
                        {{-- <li>

                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-package"></i> <!-- Alternate Cart Icon -->
                                <span key="t-maps">Stock Pricing</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('add-sell')
                                    <li><a href="{{ route('sell.index') }}" class=""><i class="bx  bx-add-to-queue"></i>
                                            Add
                                            Stock Price</a></li> <!-- Plus Icon -->
                                @endcan

                                <li><a href="{{ route('sell.list') }}" class=""><i class="bx bx-clipboard"></i> Stock
                                        Price
                                        List</a></li> <!-- List Icon -->

                            </ul>
                        </li> --}}
                    @endcan
                    @can('view-stock')
                        <li>
                            <a href="{{ route('stock.list') }}" class="">
                                <i class="bx bx-list-ul"></i> <!-- List Icon -->
                                <span key="t-dashboards">Stock List</span>
                            </a>
                        </li>
                    @endcan

                    @can('view-purchase-history')
                    <li>
                        <a href="{{ route('purchase.history') }}" class="">
                            <i class="bx bx-history"></i> <!-- List Icon -->
                            <span key="t-history">Purchase history</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-sale-history')
                    <li>
                        <a href="{{ route('sell.history') }}" class="">
                            <i class="bx bx-history"></i> <!-- List Icon -->
                            <span key="t-sale-history">Sale History</span>
                        </a>
                    </li>
                    @endcan
                    @canany(['view-role', 'view-permission-manager'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cog"></i> <!-- Settings Icon -->
                            <span key="t-maps">Settings</span>
                        </a>
                       
                        <ul class="sub-menu" aria-expanded="false">
                            @can('view-role')
                            <li><a href="{{ route('role-manager') }}" key="t-role-manager"><i
                                        class="bx bx-shield-quarter"></i> Role
                                    Manager</a></li>
                            @endcan
                            @can('view-permission-manager')
                            <li><a href="{{ route('permission-manager') }}" key="t-permission-manager"><i
                                        class="bx bx-lock-alt"></i>
                                    Permission Manager</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                {{-- @elseif(auth()->user()->role == 2) --}}
                @canany(['add-purchase', 'view-purchase'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-cart"></i> <!-- Cart Icon -->
                                <span key="t-maps">Purchase Stock</span>
                            </a>


                            <ul class="sub-menu" aria-expanded="false">
                                @can('add-purchase')
                                    <li><a href="{{ route('stock.index') }}" key="t-role-manager"><i class="bx bx-plus"></i>
                                            Add
                                            Purchase</a></li>
                                @endcan
                                @can('view-purchase')
                                <li><a href="{{ route('stock.list') }}" key="t-role-manager"><i
                                            class="bx bx-list-check"></i> Purchase List</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endcan
                {{-- @elseif(auth()->user()->role == 3) --}}
                @can('view-sell-counter')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-wallet"></i> <!-- Wallet Icon -->
                            <span key="t-maps">Sell Management</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">

                            @can('add-sell-counter')
                                <li><a href="{{ route('sellCounter.create') }}" class=""><i
                                            class="bx bx-add-to-queue"></i> Add Sell</a></li>
                                 <li><a href="{{ route('sellCounter.index') }}" class=""><i
                                            class="bx bx-list-check"></i> Sell List</a></li> 
                            @endcan
                            @can('view-order')
                                <li><a href="{{ route('sell.orders.list') }}" class=""><i class="bx bx-receipt"></i>
                                        Order List</a></li> <!-- Receipt Icon -->
                            @endcan
                        </ul>
                    </li>
                    @endcan

                  
                {{-- @endif --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
