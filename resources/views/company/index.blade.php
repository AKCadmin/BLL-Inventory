@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'brand Management']); ?>
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
                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'brand List', 'title' => 'Manage Companies']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title"></h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Create a brand
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>

                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>

                                            <th>Address</th>
                                            <th>Category</th>
                                            <th>Contact Person</th>
                                            <th>Phone no</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($brands as $brand)
                                            @php
                                                $addressLimit = 15; // Set the character limit for truncating the address
                                                $truncatedAddress =
                                                    strlen($brand->address) > $addressLimit
                                                        ? substr($brand->address, 0, $addressLimit) . '...'
                                                        : $brand->address;
                                                $descriptionLimit = 15; // Set the character limit for truncating the address
                                                $truncatedDescription =
                                                    strlen($brand->description) > $descriptionLimit
                                                        ? substr($brand->description, 0, $descriptionLimit) . '...'
                                                        : $brand->description;
                                            @endphp
                                            <tr>
                                                <td>{{ $brand->id }}</td>
                                                <td>{{ $brand->name }}</td>
                                                <td>
                                                    <span title="{{ $brand->address }}" data-toggle="tooltip"
                                                        data-placement="top">
                                                        {{ $truncatedAddress }}
                                                    </span>
                                                </td>
                                                <td>{{ $brand->category }}</td>
                                                <td>{{ $brand->contact_person }}</td>
                                                <td>{{ $brand->phone_no }}</td>
                                                <td>
                                                    <span title="{{ $brand->description }}" data-toggle="tooltip"
                                                        data-placement="top">
                                                        {{ $truncatedDescription }}
                                                    </span>
                                                </td>
                                                <td>{{ $brand->status ? 'Active' : 'Inactive' }}</td>
                                                <td>

                                                    <a href="#" class="btn btn-sm btn-warning edit-brand-btn"
                                                        data-id="{{ $brand->id }}">Edit</a>

                                                    <button class="btn btn-sm btn-danger delete-brand-btn"
                                                        data-id="{{ $brand->id }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add brand Modal -->
                <div id="brandModal" class="modal">
                    <div class="container" style="margin-top:50px;">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">
                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary" id="modal_header">Add New brand</h5>
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

                                            <form id="brandForm" method="post" action="{{ route('brand.store') }}">
                                                @csrf
                                                <input type="hidden" class="form-control" id="brand_id"
                                                    name="brand_id" value="">

                                                <div class="mb-3">
                                                    <label for="brand_name" class="form-label">brand Name</label>
                                                    <input type="text" class="form-control" id="brand_name"
                                                        name="brand_name" required placeholder="Enter brand Name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="brand_address" class="form-label">brand
                                                        Address</label>
                                                    <input type="tel" class="form-control" id="brand_address"
                                                        name="brand_address" required placeholder="Enter brand Address">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="brand_email" class="form-label">Contact Person</label>
                                                    <input type="text" class="form-control" id="brand_contact"
                                                        name="brand_contact" required placeholder="Enter contact person">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_no" class="form-label">Phone No</label>
                                                    <input type="number" class="form-control" id="phone_no"
                                                        name="phone_no" required placeholder="Enter brand Phone No">
                                                        <div id="phone_error" style="color: red; display: none;">Please enter a valid phone number (10 digits).</div>
                                                </div>

                                                <!-- Add the Category Field -->
                                                <div class="mb-3">
                                                    <label for="brand_category" class="form-label">Category</label>
                                                    <input type="text" class="form-control" id="brand_category"
                                                        name="brand_category" required
                                                        placeholder="Enter brand Category">
                                                </div>

                                                <!-- Add the Description Field -->
                                                <div class="mb-3">
                                                    <label for="brand_description"
                                                        class="form-label">Description</label>
                                                    <textarea class="form-control" id="brand_description" name="brand_description" required
                                                        placeholder="Enter brand Description"></textarea>
                                                </div>


                                                <div class="mb-3">
                                                    <label for="brand_status" class="form-label">Status</label>
                                                    <select class="form-select" id="brand_status" name="brand_status"
                                                        required>
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
        $('#brand_contact').on('keypress', function (e) {
            const charCode = e.which;
            if ((charCode >= 48 && charCode <= 57)) {
                e.preventDefault();
            }
        });
        $('#phone_no').on('input', function () {
            let phone = $(this).val();
            if (phone.length > 10) {
                $(this).val(phone.substring(0, 10)); 
            } else if (!/^\d*$/.test(phone)) {
                $(this).val(phone.replace(/\D/g, '')); 
            }
        });
        $('#openModalBtn').on('click', function() {
            $('#brandModal').show();
            $('#brand_id').val('');
            $('#brandForm')[0].reset();
            $('#modal_header').text('Add New brand');
        });


        $('.close').on('click', function() {
            $('#brandModal').hide();
        });


        $('#brandForm').on('submit', function(e) {
            e.preventDefault();

            let brandId = $('#brand_id').val();
            let brandName = $('#brand_name').val();
            let formData = $(this).serialize();
            let user = @json(auth()->user());
            let url = brandId ?
                '{{ route('brand.update', ':id') }}'.replace(':id', brandId) :
                '{{ route('brand.store') }}';
            let requestType = brandId ? 'PUT' : 'POST';

            $.ajax({
                    url: url,
                    type: requestType,
                    data: formData,
                })
                .done(function(response) {
                    if (response.success) {
                        toastr.success('brand saved successfully!');
                        $("#global-loader").fadeOut();
                        $('#brandModal').hide();
                        location.reload();
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    toastr.error('An error occurred: ' + errorThrown);
                    $("#global-loader").fadeOut();
                });
        });


        $('#datatable').on('click', '.edit-brand-btn', function() {
            let brandId = $(this).data('id');
            let url = `{{ route('brand.editbrand', '') }}/${brandId}`;

            ajaxRequest(url, 'GET', {},
                function(response) {
                    if (response.success) {
                        $('#brandModal').show();
                        $('#brand_id').val(response.brand.id);
                        $('#brand_name').val(response.brand.name);
                        $('#brand_category').val(response.brand.category);
                        $('#brand_address').val(response.brand.address);
                        $('#brand_contact').val(response.brand.contact_person);
                        $('#phone_no').val(response.brand.phone_no);
                        $('#brand_description').val(response.brand.description);
                        $('#brand_status').val(response.brand.status);
                        $('#modal_header').text('Edit brand');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                }
            );
        });


        $('#datatable').on('click', '.delete-brand-btn', function() {
            let brandId = $(this).data('id');
            let appUrl = $("#appUrl").val();
            let url = appUrl + '/brand/' + brandId;

            if (confirm('Are you sure you want to delete this product?')) {
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
        //     ajaxRequest('{{ route('brand.getData') }}', 'GET', {}, function (response) {
        //         let rows = '';
        //         response.companies.forEach(function (brand) {
        //             const charLimit = 10; 
        //             const truncatedAddress =
        //                 brand.address.length > charLimit
        //                     ? brand.address.substring(0, charLimit) + '...'
        //                     : brand.address;

        //             rows += `
        //                 <tr>
        //                     <td>${brand.id}</td>
        //                     <td>${brand.name}</td>
        //                     <td title="${brand.address}">${truncatedAddress}</td>
        //                     <td>${brand.contact_email}</td>
        //                     <td>${brand.phone_no}</td>
        //                     <td>${brand.status ? 'Active' : 'Inactive'}</td>
        //                     <td>
        //                         <button class="btn btn-warning btn-sm edit-brand" data-id="${brand.id}">Edit</button>
        //                         <button class="btn btn-danger btn-sm delete-brand" data-id="${brand.id}">Delete</button>
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
