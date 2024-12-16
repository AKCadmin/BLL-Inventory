
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
                                            <th>Permission Name</th>    
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->id }}</td>
                                                <td>{{ $permission->role_id}}</td>
                                                <td>{{ $permission->roles->role_name}}</td>
                                                <td>{{ str_replace('_', ' ', $permission->permission_name) }}</td>
                                              

                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $permission->id }}" data-toggle="toggle"
                                                            data-on="Activated" data-off="Deactivated"
                                                            {{ $permission->status == '1' ? 'checked' : '' }}>
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
                                            <form class="form-horizontal" id="permissionForm" action="permissions.add" method="post">
                                                @csrf

                                                <div class="mb-3">
                                                    <label for="permission_name" class="form-label">Permission Name</label>
                                                    <input type="text" class="form-control" id="permission_name"
                                                        name="permission_name" placeholder="E.g. Settings Permission"
                                                        value="{{ old('permission_name') }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Role</label>
                                                    <select class="form-select" id="autoSizingSelect" name="role_id">
                                                        <option value="">Select Role &ensp;</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->role_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>

                                                {{-- <div class="mb-3">
                                                    <label>Select Module</label>
                                                    <div class="row">
                                                        @foreach ($modules as $index => $module)
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="module_ids[]" value="{{ $module->id }}"
                                                                        id="module-{{ $module->id }}"
                                                                        {{ in_array($module->id, old('module_ids', [])) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="module-{{ $module->id }}">
                                                                        {{ $module->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @if (($index + 1) % 2 == 0)
                                                                <div class="w-100"></div> <!-- Creates a new row after every two checkboxes -->
                                                            @endif
                                                        @endforeach
                                                    </div>
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
