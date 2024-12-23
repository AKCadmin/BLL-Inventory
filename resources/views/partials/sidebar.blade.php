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

                @if (auth()->user()->role != 2 && auth()->user()->role != 3)
                @can('view-user-management')
                    <li>
                        <a href="{{ route('user.management') }}" class="">
                            <i class="bx bx-user"></i> <!-- User Icon -->
                            <span key="t-dashboards">User Management</span>
                        </a>
                    </li>
                    @endcan
                    @can('view-company')
                    <li><a href="{{ route('company.index') }}" key="t-role-manager"><i class="bx bx-buildings"></i>
                            Company</a></li> <!-- Building Icon -->
@endcan
@can('view-product')
                    <li><a href="{{ route('product.index') }}" key="t-role-manager"><i class="bx bx-box"></i>
                            Product</a></li> <!-- Box Icon -->
                            @endcan
                            @can('view-stock')
                    <li>
                        <a href="{{ route('stock.list') }}" class="">
                            <i class="bx bx-list-ul"></i> <!-- List Icon -->
                            <span key="t-dashboards">Stock List</span>
                        </a>
                    </li>
                    @endcan

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cog"></i> <!-- Settings Icon -->
                            <span key="t-maps">Settings</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="role-manager" key="t-role-manager"><i class="bx bx-shield-quarter"></i> Role
                                    Manager</a></li> <!-- Role Icon -->
                            <li><a href="permission-manager" key="t-permission-manager"><i class="bx bx-lock-alt"></i>
                                    Permission Manager</a></li> <!-- Lock Icon -->
                        </ul>
                    </li>
                @elseif(auth()->user()->role == 2)
                @can('view-purchase')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cart"></i> <!-- Cart Icon -->
                            <span key="t-maps">Purchase Stock</span>
                        </a>


                        <ul class="sub-menu" aria-expanded="false">
                            @can('add-purchase')
                                <li><a href="{{ route('stock.index') }}" key="t-role-manager"><i class="bx bx-plus"></i> Add
                                        Purchase</a></li>
                            @endcan
                           
                                <li><a href="{{ route('stock.list') }}" key="t-role-manager"><i
                                            class="bx bx-list-check"></i> Purchase List</a></li>
                            
                        </ul>
                    </li>
                    @endcan
                    @can('view-sell')
                        <li>

                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-cart-alt"></i> <!-- Alternate Cart Icon -->
                                <span key="t-maps">Sell Stock</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('add-sell')
                                    <li><a href="{{ route('sell.index') }}" class=""><i class="bx bx-plus"></i> Add
                                            Sell</a></li> <!-- Plus Icon -->
                                @endcan

                                <li><a href="{{ route('sell.list') }}" class=""><i class="bx bx-list-ul"></i> Sell
                                        List</a></li> <!-- List Icon -->

                            </ul>
                        </li>
                    @endcan
                @elseif(auth()->user()->role == 3)
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-wallet"></i> <!-- Wallet Icon -->
                            <span key="t-maps">Sell Management</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @can('add-sell-counter')
                                <li><a href="{{ route('sellCounter.index') }}" class=""><i
                                            class="bx bx-add-to-queue"></i> Add Sell</a></li> <!-- Queue Icon -->
                            @endcan
                            @can('view-order')
                                <li><a href="{{ route('sell.orders.list') }}" class=""><i class="bx bx-receipt"></i>
                                        Order List</a></li> <!-- Receipt Icon -->
                            @endcan
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
