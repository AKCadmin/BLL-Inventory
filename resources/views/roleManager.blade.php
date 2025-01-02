@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="assets/css/roleManager.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    @include('partials.link')
    @include('partials.head-css')


</head>

@include('partials.body')


<!-- Begin page -->
<div id="layout-wrapper">

    @include('partials.topbar')
    @include('partials.sidebar')


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Tables', 'title' => 'Data Tables']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title">Role Manager Datatable</h4>
                                    {{-- @can('has-permission','add_role') --}}
                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Add New Role
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>
                                    {{-- @endcan --}}

                                </div><br>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 rolesTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Role Name</th>
                                            <th>User Count</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>{{ $role->role_name }}</td>
                                                <td>{{ $role->user_count }}</td>

                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $role->id }}" data-toggle="toggle"
                                                            data-on="Activated" data-off="Deactivated"
                                                            {{ $role->status == '1' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
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
                                                    <h5 class="text-primary">Add New Role</h5>
                                                    <p>New role with Skote.</p>
                                                </div>
                                            </div>
                                           
                                            <div class="col-3">
                                                <span class="close">×</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div>
                                            <a href="role-manager">
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
                                            <form class="form-horizontal"  id="roleForm" action="{{route('roles.add')}}" method="post">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="useremail" class="form-label">Role Name</label>
                                                    <input type="text" class="form-control" id="role_name"
                                                        name="role_name" placeholder="E.g. Admin"
                                                        value="{{ old('role_name') }}" required>
                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label for="autoSizingSelect">Select User</label>
                                                    <select class="form-select" id="autoSizingSelect" name="user_id">
                                                        <option value="">Select User &ensp;</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div> --}}

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Current Status</label>
                                                    <select class="form-select" id="autoSizingSelect"
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

</div>
<!-- END layout-wrapper -->


@include('partials.right-sidebar')
@include('partials.vendor-scripts')
<script src="{{ asset('assets/js/customJs/validation.js') }}"></script>
{{-- 
<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- Responsive examples -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

<!-- Datatable init js -->
<script src="assets/js/pages/datatables.init.js"></script>

<script src="assets/js/app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
@include('partials.script')
<script src="assets/js/customJs/role.js"></script>
<script>
    // Automatically open the modal if showModal is true, if there are errors, or if there is a success message
    @if ((isset($showModal) && $showModal) || $errors->any() || session('success'))
        modal.style.display = "block";
    @endif
</script>

</body>

</html>
