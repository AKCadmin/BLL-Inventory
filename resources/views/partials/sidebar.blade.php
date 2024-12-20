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
                @php
                    $menusAccess = App\Models\Permission::select('menus')->where('role_id',Auth()->user()->role)->first();
                    $menus = $menusAccess ? json_decode($menusAccess->menus, true) : [];
                @endphp



                {{-- @if(auth()->user()->role != 2 && auth()->user()->role != 3) --}}
                @can('settings')
                    
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Settings</span>
                    </a>
                             
                    <ul class="sub-menu" aria-expanded="false">
                        @can('role_management')
                        <li><a href="role-manager" key="t-role-manager">Role Manager</a></li>
                        @endcan
                        @can('permission_manager')
                        <li><a href="permission-manager" key="t-permission-manager">Permission Manager</a></li> 
                        @endcan                  

                    </ul>
        
                </li>
                @endcan
                @can('user_management')
                <li>
                    <a href="{{route('user.management')}}" class="">
                        <i class="bx bx-user-circle"></i>
                        <span key="t-dashboards">User Management</span>
                    </a>
                </li>
                @endcan
                @can('company')
                <li><a href="{{route('company.index')}}" key="t-role-manager">Company</a></li>
                @endcan
                @can('product')
                <li><a href="{{route('product.index')}}" key="t-role-manager">Product</a></li>
                @endcan
                @can('stock_list')
                <li>
                    <a href="{{route('stock.list')}}" class="">
                       
                        <span key="t-dashboards">Stock List</span>
                    </a>
                </li>
                @endcan

                {{-- @if(auth()->user()->role == 2) --}}

 

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Purchase Stock</span>
                    </a>
                                
                    <ul class="sub-menu" aria-expanded="false">
                        @can('add_purchase')
                        <li><a href="{{route('stock.index')}}" key="t-role-manager"> Add Purchase</a></li>
                        @endcan
                        @can('purchase_list')
                        <li><a href="{{route('stock.list')}}" key="t-role-manager">Purchase List</a></li>
                        @endcan
                    </ul>
                </li>

                @can('sell_stock')
                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Sell Stock</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @can('add_sell')
                        <li>
                            <a href="{{route('sell.index')}}" class="">
                                
                                <span key="t-dashboards">Add Sell</span>
                            </a>
                        </li>
                        @endcan
                        @can('sell_list')
                        <li>
                            <a href="{{route('sell.list')}}" class="">
                               
                                <span key="t-dashboards">Sell List</span>
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
                @endcan

                {{-- @elseif(auth()->user()->role == 3) --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span key="t-maps">Sell Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @can('add_sell_counter')
                        <li>
                            <a href="{{route('sellCounter.index')}}" class="">
                                <span key="t-dashboards">Add Sell</span>
                            </a>
                        </li>
                        @endcan
                        @can('order_list')
                        <li>
                            <a href="{{route('sell.orders.list')}}" class="">
                                <span key="t-dashboards">Order List</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                

                {{-- @endif --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
