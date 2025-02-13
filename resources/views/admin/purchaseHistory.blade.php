@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Stock List']); ?>
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

                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Tables', 'title' => 'Stock List']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Purchase List</h4>

                                <div class="row mb-3">
                                    <div class="row mb-3">
                                        {{-- <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="brand-filter" class="form-control custom-select">
                                                    <option value="">Select Brand</option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="product-filter" class="form-control custom-select">
                                                    <option value="">Select Product</option>
                                                    {{-- @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->name }}
                                                        </option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <input type="date" id="datePicker" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4 col-sm-6 col-12">
                                        <div class="input-group">
                                            <select id="company-filter" class="form-control custom-select">
                                                <option value="">Select Organization</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->name }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-md-4 col-sm-6 col-12">
                                        <div class="input-group">
                                            <select id="sku-filter" class="form-control custom-select">
                                                <option value="">Select SKU</option>
                                                @foreach ($stocks->unique('sku') as $stock)
                                                    <option value="{{ $stock->sku }}">{{ $stock->sku }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>

                                <table id="stocktable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        {{-- <tr>
                                            <th>Id</th>

                                            <th>Product Id</th>
                                            <th>Total Buy Price</th>
                                            <th>Total No. of batches</th>
                                            <th>Total No. of Carton</th>
                                            <th>Total Item</th>
                                            <th>Missing Item</th>
                                            <th>Date</th>
                                            <th>Action</th> --}}

                                        <th>Id</th>
                                        <th>Supplier Name</th>
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th>Total Buy Price</th>
                                        <th>Total No. of Item Per Cartoon</th>
                                        <th>Total No. of Cartoons</th>
                                        <th>Invoice</th>
                                        {{-- <th>Total Items</th>
                                            <th>Missing Items</th> --}}
                                        {{-- <th>Date</th> --}}
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($stocks as $stock)
                                            @php
                                                // Check if the batch_no is present in the 'Sell' table
                                                $batchExists = \App\Models\Sell::where(
                                                    'batch_no',
                                                    $stock->batch_no,
                                                )->exists();
                                            @endphp
                                            <tr>
                                                <td>{{ $stock->batch_id }}</td>
                                                <td>{{ $stock->sku }}</td>
                                                <td>{{ $stock->batch_no }}</td>
                                                <td>{{ $stock->buy_price }}</td>
                                                <td>{{ $stock->cartons }}</td>
                                                <td>{{ $stock->total_items }}</td>
                                                <td>{{ $stock->missing_items }}</td>
                                                <td>
                                                    @can('edit-purchase')
                                                        <a href="{{ $batchExists ? '#' : route('stock.show', ['stock' => $stock->batch_id]) }}"
                                                            class="btn btn-sm btn-warning edit-stock-btn"
                                                            data-id="{{ $stock->batch_id }}"
                                                            @if ($batchExists) style="pointer-events: none; opacity: 0.6;" @endif>
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can('delete-purchase')
                                                        <button class="btn btn-sm btn-danger delete-stock-btn"
                                                            data-id="{{ $stock->batch_id }}"
                                                            @if ($batchExists) disabled @endif>
                                                            Delete
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->
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
    $(document).ready(function() {
        $('#organizationModal').show();

        $('#closeModalBtn').click(function() {
            $('#organizationModal').hide();
        });

        $('#saveSelectionBtn').click(saveSelection);

        function saveSelection() {
            const selected = $('input[name="selectedOrganization"]:checked');
            if (selected.length > 0) {
                let companyName = selected.val();
                let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
                fetchHistory(formattedName, null, productId, selectedDate);
                productsList(formattedName);
                $('#organizationModal').hide();
                $('#organization-filter').val(companyName)
            } else {
                alert('Please select an organization');
            }
        }

        var brandName = "";
        var selectedDate = new Date().toISOString().split('T')[0];
        $('#datePicker').attr('max', selectedDate);
        var productId = "";
        var brandId = "";
        $('#organization-filter').change(function(e) {
            e.preventDefault();
            let companyName = $(this).val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, null, productId, selectedDate)
            productsList(formattedName)
        });

        $('#brand-filter').change(function(e) {
            e.preventDefault();
            brandName = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, brandName)

        });

        $('#datePicker').on('change', function() {
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            selectedDate = $(this).val(); // Get the selected date
            fetchHistory(formattedName, null, productId, selectedDate)
        });


        $('#product-filter').change(function(e) {
            e.preventDefault();
            productId = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, null, productId, selectedDate)

        });

        function productsList(companyId) {
            $.ajax({
                url: '/history/products/options',
                type: 'GET',
                dataType: 'json',
                data: {
                    company: companyId,
                },
                success: function(response) {
                    if (response.products) {
                        const $select = $('#product-filter');
                        $select.empty();
                        $select.append('<option value="">Select Product</option>');
                        response.products.forEach(function(product) {
                            $select.append(
                                `<option value="${product.id}">${product.name}</option>`
                            );
                        });
                    }
                },
                error: function(error) {
                    console.error('Error fetching products:', error);
                }
            });
        }


        // function fetchHistory(companyName, brandId, productId, selectedDate) {
        //     $.ajax({
        //         url: "{{ route('purchase.getHistory') }}",
        //         method: "GET",
        //         data: {
        //             company: companyName,
        //             brandId: brandId,
        //             productId: productId,
        //             selectedDate: selectedDate
        //         },
        //         success: function(response) {
        //             var table = $('#stocktable').DataTable();
        //             table.clear(); // Clear the existing rows

        //             $.each(response.data, function(index, stock) {
        //                 const row = `
        //             <tr>
        //                 <td>${stock.batch_id}</td>
        //                 <td>${stock.batch_no}</td>
        //                 <td>${stock.buy_price}</td>
        //                 <td>${stock.cartons}</td>
        //                 <td>${stock.total_items}</td>
        //                 <td>${stock.missing_items}</td>
        //                 <td>
        //                     <a href="/purchase/details/${stock.batch_id}/${companyName}" class="btn btn-sm btn-info" target="_blank">Details</a>
        //                     <a href="/stock/${stock.batch_id}" class="btn btn-sm btn-warning edit-stock-btn" data-id="${stock.batch_id}">Edit</a>
        //                     <button class="btn btn-sm btn-danger" onclick="deleteItem(${stock.batch_id})">Delete</button>
        //                 </td>
        //             </tr>
        //         `;
        //                 table.row.add($(row)); // Add the row to the DataTable
        //             });

        //             // Redraw the table after adding rows
        //             table.draw();
        //         },
        //         error: function() {
        //             alert("Error fetching stock data.");
        //         }
        //     });
        // }

        // function fetchHistory(companyName, brandId, productId, selectedDate) {
        //     $.ajax({
        //         url: "{{ route('purchase.getHistory') }}",
        //         method: "GET",
        //         data: {
        //             company: companyName,
        //             brandId: brandId,
        //             productId: productId,
        //             selectedDate: selectedDate
        //         },
        //         success: function(response) {
        //             console.log(response);

        //             const data = response.data || {};
        //             const table = $('#stocktable').DataTable();
        //             table.clear();

        //             for (const [productKey, productDetails] of Object.entries(data)) {
        //                 const {
        //                     product_id,
        //                     product_name,
        //                     brand_name,
        //                     batches
        //                 } = productDetails;

        //                 let totalBuyPrice = 0;
        //                 let totalCartons = 0;
        //                 let totalItems = 0;
        //                 let missingItems = 0;
        //                 let created_at = "";
        //                 const totalBatches = batches.length;

        //                 batches.forEach(batch => {
        //                     // totalBuyPrice += parseFloat(batch.buy_price) * batch
        //                     // .cartons; 
        //                     totalBuyPrice += parseFloat(batch.buy_price);
        //                     totalCartons += parseInt(batch.cartons, 10);
        //                     totalItems += parseInt(batch.total_items, 10);
        //                     missingItems += parseInt(batch.missing_items, 10);
        //                     created_at = batch.created_at.split(" ")[0];

        //                 });

        //                 const row = `
        //             <tr>
        //                 <td>${product_id}</td>
        //                 <td>${product_name}</td>
        //                 <td>${totalBuyPrice.toFixed(2)}</td>
        //                  <td>${totalBatches}</td>
        //                 <td>${totalCartons}</td>
        //                 <td>${totalItems}</td>
        //                 <td>${missingItems}</td>
        //                  <td>${created_at}</td>
        //                 <td>
        //                     <a href="/purchase/details/${product_id}/${companyName}" class="btn btn-sm btn-info" target="_blank">Details</a>
        //                     <a href="#" class="btn btn-sm btn-warning edit-stock-btn" data-url="{{ route('stock.show', '') }}/${product_id}">Edit</a>

        //                     <button class="btn btn-sm btn-danger delete-stock-btn" data-id="${product_id}">Delete</button>
        //                 </td>
        //             </tr>
        //         `;
        //                 table.row.add($(row));
        //             }

        //             table.draw();
        //             $('#stocktable').on('click', '.edit-stock-btn', function(event) {
        //                 event.preventDefault();
        //                 const url = $(this).data('url');
        //                 const confirmed = confirm(
        //                     'Are you sure you want to edit this item?');
        //                 if (confirmed) {
        //                     window.location.href = url;
        //                 }
        //             });
        //         },
        //         error: function() {
        //             alert("Error fetching stock data.");
        //         }
        //     });
        // }


        function fetchHistory(companyName, brandId, productId, selectedDate) {
            $.ajax({
                url: "{{ route('purchase.getHistory') }}",
                method: "GET",
                data: {
                    company: companyName,
                    brandId: brandId,
                    productId: productId,
                    selectedDate: selectedDate
                },
                success: function(response) {


                    const data = response.data || {};
                    const table = $('#stocktable').DataTable();
                    table.clear(); // Clear existing rows

                    // Loop through the response data
                    for (const [key, productArray] of Object.entries(data)) {
                        if (Array.isArray(productArray)) {
                            productArray.forEach((productDetails) => {
                              
                                const created_at = productDetails?.created_at?.split(" ")[
                                    0] || "N/A";
                                const editUrl =
                                    `/sellCounter/${encodeURIComponent(productDetails?.order_id)}/edit`;

                                function safeBase64Encode(str) {
                                    return btoa(unescape(encodeURIComponent(str)));
                                }

                                const purchaseDetailsCreatedAt = safeBase64Encode(
                                    productDetails?.created_at.toString());
                                const row = `
                <tr>
                    <td>${productDetails?.product_id || "N/A"}</td>
                    <td>${productDetails?.brand_name || "N/A"}</td>
                    <td>${productDetails?.product_name || "N/A"}</td>
                    <td>${productDetails?.unit || "N/A"}</td>
                    <td>${productDetails?.total_buy_price || "N/A"}</td>
                    <td>${productDetails?.total_no_of_unit || "N/A"}</td>
                    <td>${productDetails?.total_quantity}</td>
                    <td>${productDetails?.invoice || "N/A"}</td>
                    
                        <td>
                            <a href="/purchase/details/${encodeURIComponent(productDetails?.product_id)}/${purchaseDetailsCreatedAt}/${productDetails?.no_of_units}" 
                               class="btn btn-sm btn-info" 
                               target="_blank">Details</a>
                            <a href="/purchaseHistory/show/${encodeURIComponent(productDetails?.product_id)}/${purchaseDetailsCreatedAt}/${productDetails?.no_of_units}" 
                               class="btn btn-sm btn-warning" target="_blank">Edit</a>
                            <button class="btn btn-sm btn-danger delete-stock-btn" 
                                    data-id="${productDetails?.product_id}">Delete</button>
                        </td>
                </tr>
            `;
                                table.row.add($(row));
                            });
                        }
                    }


                    table.draw(); // Redraw the table

                    // Bind click event for dynamically added buttons
                    $('#stocktable').off('click', '.edit-stock-btn').on('click', '.edit-stock-btn',
                        function(event) {
                            event.preventDefault();
                            const url = $(this).data('url');
                            const confirmed = confirm(
                                'Are you sure you want to edit this item?');
                            if (confirmed) {
                                window.location.href = url;
                            }
                        });
                },
                error: function() {
                    alert("Error fetching stock data.");
                }
            });
        }





        $('#stocktable').on('click', '.delete-stock-btn', function(e) {
            e.preventDefault();
            var stockId = $(this).data('id');
            var row = $(this).closest('tr');


            if (confirm('Are you sure you want to delete this sell record?')) {
                $.ajax({
                    url: '/stock/' + stockId,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success === true) {
                            row.remove();
                            toastr.success(response.message);
                            window.location.href =
                                '{{ route('purchase.history') }}';
                        } else {
                            toastr.success(
                                'Product and its related batches and cartons deleted successfully.'
                            );
                        }
                    },
                    error: function() {
                        toastr.error('Error occurred while deleting the record.');
                    }
                });
            }
        });


        // Check if the table is already initialized before initializing it again
        if (!$.fn.dataTable.isDataTable('#stocktable')) {
            var table = $('#stocktable').DataTable({
                // Optional: Enable the global search box for the entire table if needed
                // searching: true,
            });

            // SKU Filter dropdown change event
            $('#sku-filter').on('change', function() {
                var selectedSku = $(this).val();

                if (selectedSku) {
                    // Filter the table by SKU (column 1 is the SKU column)
                    table.column(1).search('^' + selectedSku + '$', true, false).draw();
                } else {
                    // Clear the filter if no SKU is selected
                    table.column(1).search('').draw();
                }
            });
        }
    });
</script>
</body>

</html>
