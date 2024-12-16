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
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboards"><?php echo $lang['Dashboard']; ?></span>
                    </a>
                </li>
                
                 
                <!-- <li class="menu-title" key="t-apps"><?php echo $lang['Apps']; ?></li> -->

                @if(auth()->user()->role == null)

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Settings</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="role-manager" key="t-role-manager">Role Manager</a></li>
                        
                        <li><a href="permission-manager" key="t-permission-manager">Permission Manager</a></li>                   

                    </ul>
                </li>
                <li>
                    <a href="{{route('user.management')}}" class="">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-dashboards">User Management</span>
                    </a>
                </li>

                <li><a href="{{route('company.index')}}" key="t-role-manager">Company</a></li>
                <li><a href="{{route('product.index')}}" key="t-role-manager">Product</a></li>
                <li>
                    <a href="{{route('stock.list')}}" class="">
                       
                        <span key="t-dashboards">Stock List</span>
                    </a>
                </li>

                @elseif(auth()->user()->role == 2)

 

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Purchase Stock</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('stock.index')}}" key="t-role-manager"> Add Purchase</a></li>
                        <li><a href="{{route('stock.list')}}" key="t-role-manager">Purchase List</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Sell Stock</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{route('sell.index')}}" class="">
                                
                                <span key="t-dashboards">Add Sell</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{route('sell.list')}}" class="">
                               
                                <span key="t-dashboards">Sell List</span>
                            </a>
                        </li>

                    </ul>
                </li>

                @elseif(auth()->user()->role == 3)
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Sell Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{route('sellCounter.index')}}" class="">
                                <span key="t-dashboards">Add Sell</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('sell.orders.list')}}" class="">
                                <span key="t-dashboards">Order List</span>
                            </a>
                        </li>
                    </ul>
                </li>
                

                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
