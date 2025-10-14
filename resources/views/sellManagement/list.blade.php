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
                                <h4 class="card-title mb-4">Sale List</h4>

                                <div class="row mb-3">
                                    <div class="row mb-3">
                                        {{-- <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="brand-filter" class="form-control custom-select">
                                                    <option value="">Select Brand</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->name }}">{{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="product-filter" class="form-control custom-select">
                                                    <option value="">Select Product</option>

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
                                        <tr>
                                            <th>Id</th>
                                            <th>Supplier Name</th>
                                            <th>Product Name</th>
                                            <th>Unit</th>
                                            <th>Total Buy Price</th>
                                             <th>Total Sell Price</th>
                                            <th>Total No. of Item Per Cartoon</th>
                                            <th>Total No. of Cartoons</th>
                                            <th>Order Id</th>
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
    window.userPermissions = @json([
        'canEdit' => auth()->user()->can('edit-sell-counter'),
        'canDelete' => auth()->user()->can('delete-sell-counter'),
    ]);
</script>
<script>
    $(document).ready(function() {
        var brandName = ""
        var productId = "";
        var selectedDate = new Date().toISOString().split('T')[0];
        // $('#datePicker').attr('max', selectedDate);
        // $('#datePicker').val(selectedDate)
        // $('#organization-filter').change(function(e) {
        //     e.preventDefault();

        // });
         let companyName = $('#organization-filter').val();
        fetchHistory(null, null, null, selectedDate)
        productsList(companyName)

        $('#organization-filter').change(function(e) {
            e.preventDefault();
            let companyName = $(this).val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, null, null, selectedDate)
            productsList(formattedName)
        });

        $('#brand-filter').change(function(e) {
            e.preventDefault();
            brandName = $(this).val();
            // let companyName = $('#organization-filter').val();
            // let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(companyName, brandName)

        });

        $('#datePicker').on('change', function() {
            // let companyName = $('#organization-filter').val();
            // let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            selectedDate = $(this).val(); // Get the selected date
            fetchHistory(companyName, null, productId, selectedDate)
        });


        $('#product-filter').change(function(e) {
            e.preventDefault();
            productId = $(this).val();
            // let companyName = $('#organization-filter').val();
            // let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(companyName, null, productId, selectedDate)

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

        function fetchHistory(companyName, brandId, productId, selectedDate) {
            $.ajax({
                url: "{{ route('sell.getHistory') }}",
                method: "GET",
                data: {
                    company: companyName,
                    brandId: brandId,
                    productId: productId,
                    selectedDate: selectedDate
                },
                success: function(response) {
                    console.log(response);

                    const data = response.data || {};
                    const table = $('#stocktable').DataTable();


                    table.clear(); // Clear existing rows

                    // Loop through the response data
                    for (const [key, productDetails] of Object.entries(data)) {
                        const created_at = productDetails?.created_at?.split(" ")[0] || "N/A";
                        const userPermissions = window.userPermissions || {};
                        const editUrl =
                        `/sellCounter/${encodeURIComponent(productDetails?.order_id)}/edit`;
                        const editButton = userPermissions.canEdit ?
                            `<a href="${editUrl}" class="btn btn-sm btn-warning">Edit</a>` :
                            '';

                        const deleteButton = userPermissions.canDelete ?
                            `<button class="btn btn-sm btn-danger delete-stock-btn" 
                             data-id="${productDetails?.product_id}">Delete</button>` : '';
                       
                        const invoiceUrl =
                            `/invoice/${encodeURIComponent(productDetails?.order_id)}/download`;

                        // Create invoice button HTML only if status is true
                        const invoiceButton = productDetails?.approve_status ?
                            `<button class="btn btn-sm btn-info download-invoice-btn" 
                        data-order-id="${productDetails?.order_id}">
                        Download Invoice
                    </button>` : '';
                        const row = `
                    <tr>
                        <td>${productDetails?.product_id || "N/A"}</td>
                        <td>${productDetails?.brand_name || "N/A"}</td>
                        <td>${productDetails?.product_name || "N/A"}</td>
                        <td>${productDetails?.unit || "N/A"}</td>
                        <td>${productDetails?.total_buy_price || "N/A"}</td>
                         <td>${productDetails?.total_sell_price || "N/A"}</td>
                        <td>${productDetails?.total_no_of_unit || "N/A"}</td>
                        <td>${productDetails?.total_quantity}</td>
                        <td>${productDetails?.order_id || "N/A"}</td>
                        
        <td>
            ${invoiceButton || `${editButton} ${deleteButton}`}  
        </td>
                    </tr>
                `;
                        table.row.add($(row)); // Add the row to DataTable
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
                    $('#stocktable').off('click', '.download-invoice-btn').on('click',
                        '.download-invoice-btn',
                        function(event) {
                            event.preventDefault();
                            const orderId = $(this).data('order-id');
                            downloadInvoice(orderId);
                        });
                },
                error: function() {
                    alert("Error fetching stock data.");
                }
            });
        }


        function downloadInvoice(orderId) {
            $.ajax({
                url: `/invoice/${orderId}/download`,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob' // Important for handling PDF/document downloads
                },
                success: function(response, status, xhr) {
                    // Create blob link to download
                    const blob = new Blob([response], {
                        type: 'application/pdf'
                    });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `invoice-${orderId}.pdf`; // Set filename
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                },
                error: function() {
                    alert("Error downloading invoice.");
                }
            });
        }


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

    function deleteItem(itemId) {
        if (confirm("Are you sure you want to delete this item?")) {

            $.ajax({
                url: '/sell/' + itemId,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status === 'success') {

                        toastr.success(response.message);
                        window.location.href =
                            '{{ route('sell.history') }}';
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function() {
                    toastr.error('Error occurred while deleting the record.');
                }
            });
        }
    }
</script>
</body>

</html>
