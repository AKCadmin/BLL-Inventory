@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>

    {{-- <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="assets/css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /> --}}
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
                                    <h4 class="card-title">Menu Datatable</h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Add New Menu
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>ID</th>
                                            <th>Menu Name</th>
                                            <th>Roles</th>
                                            <th>Status</th>
                                            <th>Trash</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menus as $index => $menu)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $menu->id }}</td>
                                                <td>{{ $menu->menu_name }}</td>
                                
                                                @php
                                                $rolesArray = json_decode($menu->roles, true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($rolesArray)) {
                                                    $rolesString = implode(', ', $rolesArray);
                                                } else {
                                                    $rolesString = 'No roles assigned'; // Or some default message
                                                }
                                                @endphp
                                                <td>{{ $rolesString }}</td>
                                                
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $menu->id }}" data-toggle="toggle"
                                                            data-on="Activated" data-off="Deactivated"
                                                            {{ $menu->status == '1' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <!-- Delete form -->
                                                    
                                                     
                                                        <button type="submit" class="btn btn-danger btn-sm delete-menu">
                                                            <i class="fa fa-trash"></i>
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
                                                    <h5 class="text-primary">Add New Menu</h5>
                                                    <p>New menu with Skote.</p>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <span class="close">×</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div>
                                            <a href="menu">
                                                <div class="avatar-md profile-user-wid mb-4">
                                                    <span class="avatar-title rounded-circle bg-light">
                                                        <img src="assets/images/logo.svg" alt=""
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
                                            <form class="form-horizontal" id="addMenuForm" action="{{route('menus.add')}}" method="post">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="menu_name" class="form-label">Menu Name</label>
                                                    <input type="text" class="form-control" id="menu_name"
                                                        name="menu_name" placeholder="E.g. Settings Menu"
                                                        value="{{ old('menu_name') }}" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label>Select Roles</label>
                                                    <div>
                                                        @foreach ($roles as $role)
                                                            <div class="checkbox-wrapper-39">
                                                                <label>
                                                                    <input type="checkbox" name="role_ids[]" id="role_{{ $role->id }}" value="{{ $role->id }}"
                                                                        {{ in_array($role->id, old('role_ids', [])) ? 'checked' : '' }}>
                                                                    <span class="checkbox"></span>
                                                                    &emsp;{{ $role->role_name }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                



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

{{-- <!-- Required datatable js -->
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

<script src="assets/js/app.js"></script> --}}

@include('partials.script')

<script src="assets/js/customJs/menu.js"></script>

</body>

</html>
