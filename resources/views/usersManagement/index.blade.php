@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>


    @include('partials.link')
    @include('partials.head-css')


</head>

@include('partials.body')


<!-- Begin page -->
<div id="layout-wrapper">

    @include('partials.topbar')
    @include('partials.sidebar')

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Tables', 'title' => 'Data Tables']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title"></h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Create a user
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($moduleusers as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->username }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->roles->role_name }}</td>

                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $item->id }}" data-toggle="toggle"
                                                            data-on="Activated" data-off="Deactivated"
                                                            {{ $item->is_verified == '1' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning edit-user-btn"
                                                        data-id="{{ $item->id }}">Edit</a>
                                                    <button class="btn btn-sm btn-danger delete-user-btn"
                                                        data-id="{{ $item->id }}">Delete</button>
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
                                                    <h5 class="text-primary" id="modal_header">Create a user</h5>
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
                                            <form class="form-horizontal" id="userForm" method="post">
                                                @csrf
                                                <input type="hidden" class="form-control" id="module_name"
                                                    name="module_name" value="">
                                                <input type="hidden" class="form-control" id="user_id" name="user_id"
                                                    value="">

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Company</label>
                                                    <select class="form-select company" id="companyId" id="autoSizingSelect"
                                                        name="company_id">
                                                        <option value="">Select Company &ensp;</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}"
                                                                {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                                {{ $company->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Role</label>
                                                    <select class="form-select role" id="autoSizingSelect"
                                                        name="role_id">
                                                        <option value="">Select Role &ensp;</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->role_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="admin_username" class="form-label">Admin
                                                        Username</label>
                                                    <input type="text" class="form-control" id="admin_username"
                                                        name="admin_username" value="{{ old('admin_username') }}"
                                                        placeholder="Enter Admin Username" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="admin_firstname" class="form-label">Admin First
                                                        Name</label>
                                                    <input type="text" class="form-control" id="admin_firstname"
                                                        name="admin_firstname" value="{{ old('admin_firstname') }}"
                                                        placeholder="Enter Admin First Name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="admin_lastname" class="form-label">Admin Last
                                                        Name</label>
                                                    <input type="text" class="form-control" id="admin_lastname"
                                                        name="admin_lastname" value="{{ old('admin_lastname') }}"
                                                        placeholder="Enter Admin Last Name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_number" class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" id="phone_number"
                                                        name="phone_number" value="{{ old('phone_number') }}"
                                                        placeholder="Enter Phone Number" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        name="email" value="{{ old('email') }}"
                                                        placeholder="Enter Email" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" placeholder="Enter Password" >
                                                </div>

                                                <div class="mb-3">
                                                    <label for="confirm_password" class="form-label">Confirm
                                                        Password</label>
                                                    <input type="password" class="form-control" id="confirm_password"
                                                        name="confirm_password" placeholder="Confirm Password"
                                                        >
                                                </div>



                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Current Status</label>
                                                    <select class="form-select" id="current_status"
                                                        id="autoSizingSelect" name="current_status" required>
                                                        <option selected value="">Current Status &ensp;</option>
                                                        <option value="1"
                                                            {{ old('current_status') == '1' ? 'selected' : '' }}>
                                                            Active
                                                        </option>
                                                        <option value="0"
                                                            {{ old('current_status') == '0' ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="text-end">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        id="submit" type="submit">Submit</button>
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

</div>
<!-- END layout-wrapper -->


@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')
<script src="assets/js/customJs/userManagement.js"></script>
<script>
    @if ((isset($showModal) && $showModal) || $errors->any() || session('success'))
        modal.style.display = "block";
    @endif
</script>

</body>

</html>
