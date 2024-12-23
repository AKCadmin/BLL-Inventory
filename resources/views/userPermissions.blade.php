<!-- In resources/views/permissions/assign.blade.php -->
@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Assign Permissions']); ?>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.link')
    @include('partials.head-css')
</head>

@include('partials.body')

<div id="layout-wrapper">
    @include('partials.topbar')
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Permissions', 'title' => 'Assign Permissions']); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Assign Permissions</h4>
                            </div>
                            <div class="card-body">
                                <!-- Select User -->
                                <form method="POST" id="permissionsForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="userSelect" class="form-label">Select User:</label>
                                        <select id="userSelect" name="user_id" class="form-select">
                                            <option value="" disabled selected>Select a user</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <!-- Permissions Table -->
                                    <div id="permissionsTable">
                                        <p>Select a user to load permissions</p>
                                    </div>
                                
                                    <!-- Save Button -->
                                    <div class="mt-3">
                                        <button class="btn btn-primary" type="button" id="savePermissionsBtn">Save Permissions</button>
                                    </div>
                                </form>
                                
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
<script>
$(document).ready(function () {
    $('#savePermissionsBtn').on('click', function () {
            const formData = $('#permissionsForm').serialize();

            $.ajax({
                url: "{{ route('assign.permissions.save') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        toastr.success('Permissions saved successfully!');
                    } else {
                        alert('Failed to save permissions. Please try again.');
                    }
                },
                error: function (error) {
                    console.error('Error saving permissions:', error);
                    alert('An error occurred while saving permissions.');
                }
            });
        });
    $('#userSelect').on('change', function () {
        const userId = $(this).val();

        if (userId) {
            $.ajax({
                url: "{{ route('assign.permissions.get') }}",
                type: "POST",
                data: {
                    user_id: userId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.length > 0) {
                        let permissionsHtml = `
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Page</th>
                                            <th>View</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        response.forEach(page => {
                            // Extract permissions if available
                            const permissions = page.permissions.length > 0 ? JSON.parse(page.permissions[0].page_permission) : [];

                            permissionsHtml += `
                                <tr>
                                    <td>${page.name}</td>
                                    <td>
                                        <input type="checkbox" name="permissions[${page.id}][]" value="1" class="form-check-input"
                                               ${permissions.includes("1") ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permissions[${page.id}][]" value="2" class="form-check-input"
                                               ${permissions.includes("2") ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permissions[${page.id}][]" value="3" class="form-check-input"
                                               ${permissions.includes("3") ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="permissions[${page.id}][]" value="4" class="form-check-input"
                                               ${permissions.includes("4") ? 'checked' : ''}>
                                    </td>
                                </tr>
                            `;
                        });

                        permissionsHtml += `
                                    </tbody>
                                </table>
                            </div>
                        `;

                        $('#permissionsTable').html(permissionsHtml);
                    } else {
                        $('#permissionsTable').html('<p class="text-warning">No permissions found for this user.</p>');
                    }
                },
                error: function (error) {
                    console.error('Error fetching permissions:', error);
                    $('#permissionsTable').html('<p class="text-danger">Failed to load permissions.</p>');
                }
            });
        }
    });
});

</script>
</body>

</html>
