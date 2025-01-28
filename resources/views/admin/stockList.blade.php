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
                                <h4 class="card-title mb-4">Stock List</h4>
                                <div class="row mb-3">
                                    {{-- <div class="col-md-4 col-sm-6 col-12">
                                        <div class="input-group">
                                            <select id="brand-filter" class="form-control custom-select">
                                                <option value="">Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
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


                                <table id="stocktable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            {{-- <th>Id</th>
                                            <th>Brand Name</th>
                                            <th>Batch No</th>
                                            <th>Product Name</th>
                                            <th>Available</th> --}}
                                            
                                            <th>Id</th>
                                            <th>Brand Name</th>
                                            <th>Product Name</th>                                       
                                            <th>Unit</th>
                                            <th>Expire Date</th>
                                            <th>No of Unit Per Cartoon</th>
                                            <th>Total No of Cartoons</th>
                                            {{-- <th>Purchased sales</th> --}}
                                            <th>Status</th>

                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    {{-- <tbody>
                                        @foreach ($stocks as $stock)
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
                                                <td>{{ $stock->cartons }}</td>
                                                <td>{{ $stock->total_items }}</td>                                              
                                                <td>
                                                    <a href="{{ $batchExists ? '#' : route('stock.show', ['stock' => $stock->batch_id]) }}"
                                                        class="btn btn-sm btn-warning edit-stock-btn"
                                                        data-id="{{ $stock->batch_id }}"
                                                        @if ($batchExists) style="pointer-events: none; opacity: 0.6;" @endif>
                                                        Edit
                                                    </a>
                                                    <button class="btn btn-sm btn-danger delete-stock-btn"
                                                        data-id="{{ $stock->batch_id }}"
                                                        @if ($batchExists) disabled @endif>
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody> --}}

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
    $(function() {
        // $('#datePicker').datepicker({
        //     format: 'mm/dd/yyyy'  // You can customize the format here
        // });

        // $('#date-range-picker').on('apply.daterangepicker', function(ev, picker) {
        //     let dateRange = $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        //     console.log(dateRange,"dateRange")
        // });

        // $('#date-range-picker').on('cancel.daterangepicker', function(ev, picker) {
        //     $(this).val('');
        // });
    });
</script>

<script>
    $(document).ready(function() {
        let table = $('#stocktable').DataTable();
        var selectedDate = new Date().toISOString().split('T')[0];
        $('#datePicker').attr('max', selectedDate);
        $('#organization-filter').val("");
        var productId = "";
        var brandId = "";

        $('#organization-filter').change(function(e) {
            e.preventDefault();
            let companyName = $(this).val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchStocks(formattedName,null,productId,selectedDate)
            productsList(formattedName)
            //  fetchProducts(formattedName)

        });
        $('#brand-filter').change(function(e) {
            e.preventDefault();

             brandId = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchStocks(formattedName, brandId, productId,selectedDate)

        });
        $('#product-filter').change(function(e) {
            e.preventDefault();
             productId = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchStocks(formattedName, null, productId,selectedDate)

        });

        $('#datePicker').on('change', function () {
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
             selectedDate = $(this).val(); // Get the selected date
             fetchStocks(formattedName, null,productId,selectedDate)
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

        function fetchStocks(companyName, brandId, productId,selectedDate) {
            $.ajax({
                url: "{{ route('stocks.bycompany') }}",
                method: "GET",
                data: {
                    company: companyName,
                    brandId: brandId,
                    productId: productId,
                    selectedDate: selectedDate
                },
                success: function(data) {
                    
                    var table = $('#stocktable').DataTable();
                    table.clear();

                    // Append new rows
                    $.each(data, function(index, stock) {
                        console.log(stock,"stocks");
                        // var batchExists = stock.batch_no ? true : false;
                        
                            // `<a href="#" class="btn btn-sm btn-warning edit-stock-btn" style="pointer-events: none; opacity: 0.6;" disabled>Edit</a>` :
                            var editButton = 
        `<a href="/stock/${stock.product_id}" class="btn btn-sm btn-warning edit-stock-btn">Edit</a>`;

                         var deleteButton =
                            // `<button class="btn btn-sm btn-danger delete-stock-btn" disabled>Delete</button>` :
                            `<button class="btn btn-sm btn-danger delete-stock-btn" data-id="${stock.product_id}">Delete</button>`;

                        table.row.add([
                            stock.product_id,
                            stock.brand_name,
                            stock.product_name,   
                            stock.unit,
                            stock.expiry_date,
                            stock.total_no_of_unit,
                            stock.total_quantity,
                            stock.status,
                            // `${editButton} ${deleteButton}`
                        ]);
                    });

                    // Redraw the table
                    table.draw();
                },
                error: function() {
                    alert("Error fetching stock data.");
                }
            });
        }

        // Initialize DataTable once on page load
        $(document).ready(function() {
            $('#stocktable').DataTable();
        });


        function fetchProducts(companyName) {
            $.ajax({
                url: "{{ route('stocks.byproduct') }}",
                method: "GET",
                data: {
                    company: companyName
                },
                success: function(data) {
                    console.log(data, "data");

                    // Clear the existing options
                    $('#product-filter').empty();

                    // Add the default "Select Product" option
                    $('#product-filter').append('<option value="">Select Product</option>');

                    // Loop through the data and add options
                    data.forEach(function(product) {
                        $('#product-filter').append(
                            `<option value="${product.id}">${product.name}</option>`
                        );
                    });
                },
                error: function() {
                    alert("Error fetching stock data.");
                }
            });
        }


        $('#product-filter').on('change', function(e) {
            e.preventDefault()
            var selectedProduct = $(this).val();
            var companyName = $('#company-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchStocks(formattedName, selectedProduct)
            // console.log(selectedProduct, "selectedProduct")

            // if (selectedProduct) {
            //     table.column(1).search('^' + selectedProduct + '$', true, false).draw();
            // } else {
            //     table.column(1).search('').draw();
            // }
        });
        if (!$.fn.dataTable.isDataTable('#stocktable')) {


            // // Batch Filter dropdown change event
            // $('#batch-filter').on('change', function() {
            //     var selectedBatch = $(this).val();

            //     if (selectedBatch) {
            //         // Filter the table by Batch (column 2 is the Batch column)
            //         table.column(2).search('^' + selectedBatch + '$', true, false).draw();
            //     } else {
            //         // Clear the filter if no Batch is selected
            //         table.column(2).search('').draw();
            //     }
            // });
        }

    });
</script>

</body>

</html>
