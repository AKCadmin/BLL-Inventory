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
                                    <div class="row mb-3">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="brand-filter" class="form-control custom-select">
                                                    <option value="">Select Brand</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                       
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
                                            <th>Batch No</th>
                                            <th>Hospital Price</th>
                                            <th>Wholesale Price</th>
                                            <th>Retail Price</th>
                                            <th>Valid From</th>
                                            <th>Valid To</th>
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
        var brandName = ""
        var selectedDate = new Date().toISOString().split('T')[0];
        $('#datePicker').attr('max', selectedDate);
        $('#organization-filter').change(function(e) {
            e.preventDefault();
            let companyName = $(this).val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName)
            productsList(formattedName)
        });

        $('#brand-filter').change(function(e) {
            e.preventDefault();
               brandName = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, brandName)

        });

        $('#datePicker').on('change', function () {
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
             selectedDate = $(this).val(); // Get the selected date
            fetchHistory(formattedName, null,null,selectedDate)
        });

        
        $('#product-filter').change(function(e) {
            e.preventDefault();
            let productId = $(this).val();
            let companyName = $('#organization-filter').val();
            let formattedName = companyName.toLowerCase().replace(/\s+/g, '_');
            fetchHistory(formattedName, null, productId,selectedDate)

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

        function fetchHistory(companyName, brandId, productId,selectedDate) {
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
                    const data = response.data;
                    const tableBody = $('#stocktable tbody');
                    tableBody.empty();

                    data.forEach(function(item) {
                        const row = `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.batch_no}</td>
                        <td>${item.hospital_price}</td>
                        <td>${item.wholesale_price}</td>
                        <td>${item.retail_price}</td>
                        <td>${item.valid_from}</td>
                        <td>${item.valid_to}</td>
                       <td>
                            <button class="btn btn-primary btn-sm" onclick="editItem(${item.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteItem(${item.id})">Delete</button>
                        </td>
                    </tr>
                `;
                        tableBody.append(row);
                    });
                },
                error: function() {
                    alert("Error fetching stock data.");
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
</script>
</body>

</html>
