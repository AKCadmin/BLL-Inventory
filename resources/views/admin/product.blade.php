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
                                        id="createProduct">Create a product
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>
                                <div class="row mb-3">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="input-group">
                                            <select id="product-filter" class="form-control custom-select">
                                                <option value="">Select Company</option>
                                                @foreach ($products->unique(fn($product) => $product->company->name ?? "N/A") as $product)
                                                    <option value="{{ $product->company->name ?? 'N/A' }}">
                                                        {{ $product->company->name ?? 'N/A' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <table id="producttable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Company Name</th>
                                            <th>SKU</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            @php
                                                $charLimit = 10; // Set the character limit for truncating the description
                                                $truncatedDescription =
                                                    strlen($product->description) > $charLimit
                                                        ? substr($product->description, 0, $charLimit) . '...'
                                                        : $product->description;
                                            @endphp
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td>{{ $product->company ? $product->company->name : 'N/A' }}</td>
                                                <td>{{ $product->sku }}</td>
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    <span title="{{ $product->description }}" data-toggle="tooltip"
                                                        data-placement="top">
                                                        {{ $truncatedDescription }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-status"
                                                            data-id="{{ $product->id }}" data-toggle="toggle"
                                                            data-on="Available" data-off="Unavailable"
                                                            {{ $product->status == 'available' ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning edit-product-btn"
                                                        data-id="{{ $product->id }}">Edit</a>
                                                    <button class="btn btn-sm btn-danger delete-product-btn"
                                                        data-id="{{ $product->id }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div id="productModal" class="modal">
                    <div class="container" style="margin-top:50px ">
                        <div class="row justify-content-center">

                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">

                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary" id="modal_header">Create a Product</h5>
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

                                            <form class="form-horizontal" id="productForm" method="post">
                                                @csrf
                                                <input type="hidden" class="form-control" id="product_id"
                                                    name="product_id" value="">

                                                <div class="mb-3">
                                                    <label for="autoSizingSelect">Select Company</label>
                                                    <select class="form-select company" id="companyId"
                                                        id="autoSizingSelect" name="company_id">
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
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" id="sku"
                                                        name="sku" value="{{ old('sku') }}"
                                                        placeholder="Enter Product SKU" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Product Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" value="{{ old('name') }}"
                                                        placeholder="Enter Product Name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Product Description" required>{{ old('description') }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select status" id="" name="status"
                                                        required>
                                                        <option selected value="">Select Status &ensp;</option>
                                                        <option value="available"
                                                            {{ old('status') == 'available' ? 'selected' : '' }}>
                                                            Available
                                                        </option>
                                                        <option value="unavailable"
                                                            {{ old('status') == 'unavailable' ? 'selected' : '' }}>
                                                            Unavailable</option>
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
<script src="assets/js/customJs/productManagement.js"></script>


<script>
    $(document).ready(function() {

        $('#createProduct').on('click', function() {
            $('#productModal').show();
            $('#peoduct_id').val('');
            $('#productForm')[0].reset();
            $('#modal_header').text('Add New Product');
        });
        $('.close').on('click', function() {
            $('#productModal').hide();
        });

        ajaxRequest(
            "/company/data",
            "GET",
            null,
            function(data) {
                console.log(data, "data");
                $("#companyId")
                    .empty()
                    .append('<option value="">Select a company</option>');
                $.each(data.companies, function(index, company) {
                    $("#companyId").append(
                        '<option value="' +
                        company.id +
                        '">' +
                        company.name +
                        "</option>"
                    );
                });
            },
            function(error) {
                console.log("Error fetching categories:", error);
            }
        );

        // Handle Product Form Submission
        $('#productForm').on('submit', function(e) {
            e.preventDefault();

            let productId = $('#product_id').val();
            let formData = $(this).serialize();
            let url = productId ?
                '{{ route('product.update', ':id') }}'.replace(':id', productId) :
                '{{ route('product.store') }}';
            let requestType = productId ? 'PUT' : 'POST';

            ajaxRequest(url, requestType, formData,
                function(response) {
                    if (response.success) {
                        toastr.success('Product saved successfully!');
                        $('#productModal').hide();
                        location.reload();
                        // loadProducts();
                    } else {
                        toastr.error('Error: ' + response.error);
                    }
                },
                function(error) {
                    toastr.error(error.responseJSON.error);
                }
            );
        });

        // Edit Product
        $(document).on('click', '.edit-product-btn', function() {

            let productId = $(this).data('id');
            let appUrl = $("#appUrl").val();
            let url = appUrl + "/product/" + productId + "/edit";

            ajaxRequest(url, 'GET', {},
                function(response) {
                    if (response.success) {
                        $('#productModal').show();
                        console.log(response, "response")
                        $('#companyId').val(response.product.company_id)
                        $('#companyId option').each(function() {
                            console.log($(this).val(), "anaan", response.product.company_id)
                            if ($(this).val() == response.product.company_id) {
                                $(this).prop('selected', true);
                            } else {
                                $(this).prop('selected', false);
                            }
                        });
                        $('#product_id').val(response.product.id);
                        $('#sku').val(response.product.sku);
                        $('#name').val(response.product.name);
                        $('#description').val(response.product.description);
                        $('.status').val(response.product.status);
                        $('#modal_header').text('Edit Product');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                }
            );
        });

        // Delete Product
        $('#datatable').on('click', '.delete-product-btn', function() {
            let productId = $(this).data('id');
            let appUrl = $("#appUrl").val();
            let url = appUrl + '/product/' + productId;

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
                            // loadProducts();
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

        $(document).on("change", ".toggle-status", function() {
            var permissionId = $(this).data("id");
            var status = $(this).prop("checked") ? "available" : "unavailable";

            $.ajax({
                url: "/product/toggle-status",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    id: permissionId,
                    status: status,
                },
                success: function(response) {
                    if (response.success) {
                        $("#global-loader").fadeOut();
                        toastr.success("Status updated successfully");
                        loaction.reload();
                    } else {
                        $("#global-loader").fadeOut();
                        toastr.error("Failed to update status");
                    }
                },
                error: function() {
                    $("#global-loader").fadeOut();
                    toastr.error("Error updating status");
                },
            });
        });

        if (!$.fn.dataTable.isDataTable('#producttable')) {
            var table = $('#producttable').DataTable({
                // Optional: Enable the global search box for the entire table if needed
                // searching: true,
                order: [[0, 'desc']]
            });

            $('#product-filter').on('change', function() {
                var selectedProduct = $(this).val();
                if (selectedProduct) {
                    table.column(1).search('^' + selectedProduct + '$', true, false).draw();
                } else {
                    table.column(1).search('').draw();
                }
            });
        }


        // Load Products
        // function loadProducts() {
        //     ajaxRequest('{{ route('product.getData') }}', 'GET', {}, function(response) {
        //         let rows = '';
        //         response.products.forEach(function(product) {
        //             const charLimit = 10;
        //             const truncatedDescription =
        //                 product.description.length > charLimit ?
        //                 product.description.substring(0, charLimit) + '...' :
        //                 product.description;

        //             rows += `
        //         <tr>
        //             <td>${product.id}</td>
        //             <td>${product?.company?.name ?? ''}</td>
        //             <td>${product.sku}</td>
        //             <td>${product.name}</td>
        //             <td title="${product.description}">${truncatedDescription}</td>
        //             <td>${product.status == 'available' ? 'available' : 'unavailable'}</td>
        //             <td>
        //                 <button class="btn btn-warning btn-sm edit-product" data-id="${product.id}">Edit</button>
        //                 <button class="btn btn-danger btn-sm delete-product" data-id="${product.id}">Delete</button>
        //             </td>
        //         </tr>
        //     `;
        //         });
        //         $('#datatable tbody').html(rows);
        //     });
        // }


        // loadProducts();
    });
</script>


</body>

</html>
