@extends('app')
@section('content')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                @include('partials.title-meta', ['title' => 'Data Tables'])

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title">Permission Manager Datatable</h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Add New Permission
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Role ID</th>
                                            <th>Role Name</th>
                                            <th>Menus Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->id }}</td>
                                                <td>{{ $permission->role_id }}</td>
                                                <td>{{ $permission->roles->role_name }}</td>
                                                <td>
                                                    @php
                                                        $menus = json_decode($permission->menus, true);
                                                    @endphp
                                                    @if (!empty($menus) && is_array($menus))
                                                        <ul>
                                                            @foreach ($menus as $menu)
                                                                <li>{{ $menu }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        No menus assigned
                                                    @endif
                                                </td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $permission->id }}" data-toggle="toggle"
                                                            data-on="Activated" data-off="Deactivated"
                                                            {{ $permission->status == '1' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm edit-permission"
                                                        data-id="{{ $permission->id }}"
                                                        data-role="{{ $permission->role_id }}"
                                                        data-status="{{ $permission->status }}"
                                                        data-menus="{{ $permission->menus }}">
                                                        Edit
                                                    </button>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div id="myModal" class="modal">
                    <div class="container" style="margin-top:50px ">
                        <div class="row justify-content-center">

                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">

                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary">Add New Permission</h5>
                                                    <p>New permission with Skote.</p>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <span class="close">×</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div>
                                            <a href="permission-manager">
                                                <div class="avatar-md profile-user-wid mb-4">
                                                    <span class="avatar-title rounded-circle bg-light">
                                                        <img src="assets/images/logo.png" alt=""
                                                            class="rounded-circle" height="34">
                                                    </span>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="p-2">
                                            <!-- Display Validation Errors -->
                                            <div id="errorAlert">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first() }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Display Success Message -->
                                            <div id="successAlert">
                                                @if (session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <form class="form-horizontal" id="permissionForm" action="permissions.add"
                                                method="post">
                                                @csrf
                                                <input type="hidden" id="menuId" name="menuId" value="">
                                                {{-- <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Role</label>
                                                    <select class="form-select" id="autoSizingSelect" name="role_id">
                                                        <option value="">Select Role &ensp;</option>
                                                        @foreach ($availableRoles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->role_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    

                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Role</label>
                                                    <select class="form-select" id="autoSizingSelect" name="role_id">
                                                        <option value="">Select Role &ensp;</option>
                                                        <!-- Options will be appended here by AJAX -->
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="menu_options" class="form-label">Menu Options</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dashboard"
                                                            name="menu_options[]" value="dashboard"
                                                            {{ in_array('dashboard', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="dashboard">Dashboard</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="settings"
                                                            name="menu_options[]" value="settings"
                                                            {{ in_array('settings', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="settings">Settings</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="role_management"
                                                            name="menu_options[]" value="role_management"
                                                            {{ in_array('role_management', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="role_management">Role
                                                            Management</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="permission_manager" name="menu_options[]"
                                                            value="permission_manager"
                                                            {{ in_array('permission_manager', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="permission_manager">Permission Manager</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="user_management" name="menu_options[]"
                                                            value="user_management"
                                                            {{ in_array('user_management', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="user_management">User
                                                            Management</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="company"
                                                            name="menu_options[]" value="company"
                                                            {{ in_array('company', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="company">Company</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="product"
                                                            name="menu_options[]" value="product"
                                                            {{ in_array('product', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="product">Product</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="stock_list"
                                                            name="menu_options[]" value="stock_list"
                                                            {{ in_array('stock_list', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="stock_list">Stock
                                                            List</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="add_purchase"
                                                            name="menu_options[]" value="add_purchase"
                                                            {{ in_array('add_purchase', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="add_purchase">Add
                                                            Purchase</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="purchase_list" name="menu_options[]"
                                                            value="purchase_list"
                                                            {{ in_array('purchase_list', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="purchase_list">Purchase
                                                            List</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="add_sell"
                                                            name="menu_options[]" value="add_sell"
                                                            {{ in_array('add_sell', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="add_sell">Add Sell</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="add_sell_counter" name="menu_options[]"
                                                            value="add_sell_counter"
                                                            {{ in_array('add_sell_counter', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="add_sell_counter">Add Sell
                                                            Counter</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sell_stock"
                                                            name="menu_options[]" value="sell_stock"
                                                            {{ in_array('sell_stock', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sell_stock">Add Sell
                                                            Counter</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="sell_list"
                                                            name="menu_options[]" value="sell_list"
                                                            {{ in_array('sell_list', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="sell_list">Sell List</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="order_list"
                                                            name="menu_options[]" value="order_list"
                                                            {{ in_array('order_list', old('menu_options', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="order_list">Order
                                                            List</label>
                                                    </div>
                                                </div>


                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Current Status</label>
                                                    <select class="form-select menuStatus" id="autoSizingSelect"
                                                        name="current_status" required>
                                                        <option selected value="">Current Status &ensp;</option>
                                                        <option value="1"
                                                            {{ old('current_status') == '1' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="0"
                                                            {{ old('current_status') == '0' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="text-end">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">Submit</button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        @include('partials.footer')
    </div>
    <!-- end main content-->
@endsection
@section('script')
    <script src="assets/js/customJs/permissionManager.js"></script>
    <script>
        // Automatically open the modal if showModal is true, if there are errors, or if there is a success message
        @if ((isset($showModal) && $showModal) || $errors->any() || session('success'))
            modal.style.display = "block";
        @endif
    </script>
@endsection
</body>

</html>
