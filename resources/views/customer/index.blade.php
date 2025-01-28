@include('partials.session')
@include('partials.main')
@include('partials.head')
<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'brand Management']); ?>
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
                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Sales User List', 'title' => 'Manage Sales Users']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title"></h4>
                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openSaleUserModalBtn">Create Sales User
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>
                                </div><br>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Organization</th>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Address</th>
                                            <th>Credit Limit</th>
                                            <th>Payment Days</th>
                                            <th>Type of Customer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesUsers as $user)
                                            @php
                                                $addressLimit = 15; // Truncate the address for better readability
                                                $truncatedAddress =
                                                    strlen($user->address) > $addressLimit
                                                        ? substr($user->address, 0, $addressLimit) . '...'
                                                        : $user->address;
                                            @endphp
                                            <tr>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->organization ? Str::replaceFirst('_', ' ', $user->organization->name) : null }}
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->phone_number }}</td>
                                                <td>
                                                    <span title="{{ $user->address }}" data-toggle="tooltip"
                                                        data-placement="top">
                                                        {{ $truncatedAddress }}
                                                    </span>
                                                </td>
                                                <td>{{ $user->credit_limit }}</td>
                                                <td>{{ $user->payment_days }}</td>
                                                <td>{{ $user->type_of_customer }}</td>
                                                <td>{{ $user->sale_user_status ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning edit-customer-btn"
                                                        data-id="{{ $user->id }}">Edit</a>
                                                    <button class="btn btn-sm btn-danger delete-customer-btn"
                                                        data-id="{{ $user->id }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Sales User Modal -->
                <div id="salesUserModal" class="modal" style="display: none;">
                    <div class="container" style="margin-top:50px ">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">
                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary" id="modal_header">Add New Sales User</h5>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <span class="close" id="closeModal">×</span>
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

                                            <form id="salesUserForm" method="post" action="">
                                                @csrf
                                                <input type="hidden" class="form-control" id="user_id" name="user_id"
                                                    value="">

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Organization</label>
                                                    <select class="form-select organization" id="organizationId"
                                                        id="autoSizingSelect" name="organization_id">
                                                        <option value="">Select Organization &ensp;</option>
                                                        @foreach ($organizations as $organization)
                                                            <option value="{{ $organization->id }}"
                                                                {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
                                                                {{ Str::replaceFirst('_', ' ', $organization->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" required placeholder="Enter Name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_number" class="form-label">Phone Number</label>
                                                    <input type="tel" class="form-control" id="phone_number"
                                                        name="phone_number" required placeholder="Enter Phone Number">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" required placeholder="Enter Address">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="credit_limit" class="form-label">Credit Limit</label>
                                                    <input type="number" class="form-control" id="credit_limit"
                                                        name="credit_limit" required placeholder="Enter Credit Limit">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="payment_days" class="form-label">Payment Days</label>
                                                    <input type="number" class="form-control" id="payment_days"
                                                        name="payment_days" required placeholder="Enter Payment Days">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="type_of_customer" class="form-label">Type of
                                                        Customer</label>
                                                    <select class="form-select customer-type" name="type_of_customer"
                                                        id="type_of_customer" required>
                                                        <option value="" disabled selected>Select customer type
                                                        </option>
                                                        <option value="hospital">hospital</option>
                                                        <option value="wholesale">wholesaler</option>
                                                        <option value="retailer">retailer</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" id="sale_user_status"
                                                        name="sale_user_status" required>
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
<script src="{{ asset('assets/js/customJs/validation.js') }}"></script>
<script>
    $(document).ready(function() {

        $('#phone_number').on('input', function() {
            let phone = $(this).val();
            if (phone.length > 10) {
                $('#phone-error').text('Phone number must be 10 digits.');
                $(this).val(phone.substring(0, 10));
            } else if (!/^\d*$/.test(phone)) {
                $(this).val(phone.replace(/\D/g, ''));
                $('#phone-error').text('');
            }
        });


        $('#openSaleUserModalBtn').on('click', function() {
            console.log("working")
            $('#salesUserModal').show();
            $('#user_id').val('');
            $('#salesUserForm')[0].reset();
            $('#modal_header').text('Add New Sale User');
        });


        $('#closeModal').on('click', function() {
            $('#salesUserModal').hide();
        });


        $('#salesUserForm').on('submit', function(e) {
            e.preventDefault();

            let hasErrors = false;
            $('#phone-error').text('');

            let phone = $("#phone_number").val();
            if (phone.length !== 10 || !/^\d{10}$/.test(phone)) {
                $('#phone-error').text('Phone number must be 10 digits.');
                hasErrors = true;
            }

            if (hasErrors) {
                return;
            }

            let userId = $('#user_id').val();
            let formData = $(this).serialize();
            let user = @json(auth()->user());
            let url = userId ?
                '{{ route('customer.update', ':id') }}'.replace(':id', userId) :
                '{{ route('customer.store') }}';
            let requestType = userId ? 'PUT' : 'POST';

            $.ajax({
                    url: url,
                    type: requestType,
                    data: formData,
                })
                .done(function(response) {
                    if (response.success) {
                        toastr.success('Sale user saved successfully!');
                        $("#global-loader").fadeOut();
                        $('#salesUserModal').hide();
                        location.reload();
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    toastr.error('An error occurred: ' + errorThrown);
                    $("#global-loader").fadeOut();
                });
        });


        $('#datatable').on('click', '.edit-customer-btn', function() {
            let customerId = $(this).data('id');
            let url = `{{ route('customer.edit', ':id') }}`.replace(':id', customerId);

            ajaxRequest(url, 'GET', {}, function(response) {
                if (response.success) {
                    $('#user_id').val(response.customer.id);
                    $('#organizationId').val(response.customer.organization_id).trigger(
                        'change');
                    $('#name').val(response.customer.name);
                    $('#phone_number').val(response.customer.phone_number);
                    $('#address').val(response.customer.address);
                    $('#credit_limit').val(response.customer.credit_limit);
                    $('#payment_days').val(response.customer.payment_days);
                    $('#type_of_customer').val(response.customer.type_of_customer).trigger(
                        'change');
                    $('#sale_user_status').val(response.customer.sale_user_status == true ? 1 :
                        0);
                    $('#salesUserModal').show();
                } else {
                    toastr.error('Error: ' + response.message);
                }
            });
        });


        $('#datatable').on('click', '.delete-customer-btn', function() {
            let customerId = $(this).data('id'); 
            let url = `{{ route('customer.destroy', ':id') }}`.replace(':id',
            customerId); 

            if (confirm('Are you sure you want to delete this customer?')) {
                ajaxRequest(url, 'DELETE', {}, function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        window.reload();
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                });
            }
        });

    });
</script>

</body>

</html>
