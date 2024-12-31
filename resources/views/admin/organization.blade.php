@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'organization Management']); ?>
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
                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'organization List', 'title' => 'Manage Companies']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title"></h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Create a Organization
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Email</th>
                                            <th>Phone no</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($organizations as $organization)
                                            @php
                                                $addressLimit = 15; // Set the character limit for truncating the address
                                                $truncatedAddress =
                                                    strlen($organization->address) > $addressLimit
                                                        ? substr($organization->address, 0, $addressLimit) . '...'
                                                        : $organization->address;
                                            @endphp
                                            <tr>
                                                <td>{{ $organization->id }}</td>
                                                <td>{{ $organization->name }}</td>
                                                <td>
                                                    <span title="{{ $organization->address }}" data-toggle="tooltip"
                                                        data-placement="top">
                                                        {{ $truncatedAddress }}
                                                    </span>
                                                </td>
                                                <td>{{ $organization->contact_email }}</td>
                                                <td>{{ $organization->phone_no }}</td>
                                                <td>{{ $organization->status ? 'Active' : 'Inactive' }}</td>
                                                <td>

                                                    <a href="#"
                                                        class="btn btn-sm btn-warning edit-organization-btn"
                                                        data-id="{{ $organization->id }}">Edit</a>

                                                    <button class="btn btn-sm btn-danger delete-organization-btn"
                                                        data-id="{{ $organization->id }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add organization Modal -->
                <div id="organizationModal" class="modal">
                    <div class="container" style="margin-top:50px;">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">
                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary" id="modal_header">Add New organization</h5>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <span class="close">×</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="p-2">
                                            <!-- Validation Errors -->
                                            <div id="errorAlert">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first() }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Success Message -->
                                            <div id="successAlert">
                                                @if (session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <form id="organizationForm" method="post"
                                                action="{{ route('organization.store') }}">
                                                @csrf
                                                <input type="hidden" class="form-control" id="organization_id"
                                                    name="organization_id" value="">
                                                {{-- <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Brand</label>
                                                    <select class="form-select brand" id="brand_id"
                                                        id="autoSizingSelect" name="brand_id">
                                                        <option value="">Select Organization &ensp;</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label for="organization_name" class="form-label">organization
                                                        Name</label>
                                                    <input type="text" class="form-control" id="organization_name"
                                                        name="organization_name" required
                                                        placeholder="Enter organization Name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="organization_address" class="form-label">organization
                                                        Address</label>
                                                    <input type="tel" class="form-control" id="organization_address"
                                                        name="organization_address" required
                                                        placeholder="Enter organization Address">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="organization_email" class="form-label">organization
                                                        Email</label>
                                                    <input type="email" class="form-control" id="organization_email"
                                                        name="organization_email" required
                                                        placeholder="Enter organization Email">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_no" class="form-label">Phone No</label>
                                                    <input type="number" class="form-control" id="phone_no"
                                                        name="phone_no" required
                                                        placeholder="Enter organization Phone No">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="organization_status" class="form-label">Status</label>
                                                    <select class="form-select" id="organization_status"
                                                        name="organization_status" required>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
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
        </div> <!-- End Page-content -->

        @include('partials.footer')
    </div> <!-- end main content -->

</div>

@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')
<script>
    $(document).ready(function() {
        $('#organization-filter').hide();
        $('#phone_no').on('input', function () {
            let phone = $(this).val();
            if (phone.length > 10) {
                $(this).val(phone.substring(0, 10)); 
            } else if (!/^\d*$/.test(phone)) {
                $(this).val(phone.replace(/\D/g, '')); 
            }
        });
        $('#openModalBtn').on('click', function() {
            $('#organizationModal').show();
            $('#organization_id').val('');
            $('#organizationForm')[0].reset();
            $('#modal_header').text('Add New organization');
        });


        $('.close').on('click', function() {
            $('#organizationModal').hide();
        });


        $('#organizationForm').on('submit', function(e) {
            e.preventDefault();

            let organizationId = $('#organization_id').val();
            let organizationName = $('#organization_name').val();
            let formData = $(this).serialize();
            let user = @json(auth()->user());
            let url = organizationId ?
                '{{ route('organization.update', ':id') }}'.replace(':id', organizationId) :
                '{{ route('organization.store') }}';
            let requestType = organizationId ? 'PUT' : 'POST';

            $.ajax({
                    url: url,
                    type: requestType,
                    data: formData,
                })
                .done(function(response) {
                    if (response.success) {

                        $.ajax({
                                url: "/api/organization/migration",
                                type: "POST",
                                dataType: 'json',
                                data: {
                                    database_name: organizationName,
                                    user: user,
                                },
                            })
                            .done(function(response) {
                                if (response.success) {

                                    toastr.success('brand saved successfully!');
                                    $("#global-loader").fadeOut();
                                    $('#brandModal').hide();
                                    location.reload();
                                } else {
                                    toastr.error("Database migration failed.");
                                }
                            })
                            .fail(handleError);
                        // loadCompanies();
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(handleError);
        });

        function handleError(xhr, status, error) {
            let errorMessage = 'An error occurred while processing your request.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                errorMessage = xhr.responseText;
            }

            toastr.error(errorMessage);
            console.error('Error:', error);
        }



        $('#datatable').on('click', '.edit-organization-btn', function() {
            let organizationId = $(this).data('id');
            let url = `{{ route('organization.editorganization', '') }}/${organizationId}`;

            ajaxRequest(url, 'GET', {},
                function(response) {
                    if (response.success) {
                        $('#organizationModal').show();
                        // $('#brand_id').val(response.organization.brand_id);
                        $('#organization_id').val(response.organization.id);
                        $('#organization_name').val(response.organization.name);
                        $('#organization_address').val(response.organization.address);
                        $('#organization_email').val(response.organization.contact_email);
                        $('#phone_no').val(response.organization.phone_no);
                        $('#organization_status').val(response.organization.status);
                        $('#modal_header').text('Edit organization');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                }
            );
        });


        $('#datatable').on('click', '.delete-organization-btn', function() {
            let organizationId = $(this).data('id');
            let appUrl = $("#appUrl").val();
            let url = appUrl + '/organization/' + organizationId;

            if (confirm('Are you sure you want to delete this organization?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Product deleted successfully!');
                            location.reload();
                            // loadCompanies();
                        } else {
                            toastr.error('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        toastr.error(
                            'An error occurred while trying to delete the product.');
                    }
                });
            }
        });

        //         function loadCompanies() {
        //     ajaxRequest('{{ route('organization.getData') }}', 'GET', {}, function (response) {
        //         let rows = '';
        //         response.companies.forEach(function (organization) {
        //             const charLimit = 10; 
        //             const truncatedAddress =
        //                 organization.address.length > charLimit
        //                     ? organization.address.substring(0, charLimit) + '...'
        //                     : organization.address;

        //             rows += `
        //                 <tr>
        //                     <td>${organization.id}</td>
        //                     <td>${organization.name}</td>
        //                     <td title="${organization.address}">${truncatedAddress}</td>
        //                     <td>${organization.contact_email}</td>
        //                     <td>${organization.phone_no}</td>
        //                     <td>${organization.status ? 'Active' : 'Inactive'}</td>
        //                     <td>
        //                         <button class="btn btn-warning btn-sm edit-organization" data-id="${organization.id}">Edit</button>
        //                         <button class="btn btn-danger btn-sm delete-organization" data-id="${organization.id}">Delete</button>
        //                     </td>
        //                 </tr>
        //             `;
        //         });
        //         $('#datatable tbody').html(rows);
        //     });
        // }

        // loadCompanies();
    });
</script>

</body>

</html>
