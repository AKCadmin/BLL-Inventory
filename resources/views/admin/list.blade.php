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

                                        {{-- <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <select id="product-filter" class="form-control custom-select">
                                                    <option value="">Select Product</option>
                                                    
                                                </select>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="col-md-4 col-sm-6 col-12">
                                            <div class="input-group">
                                                <input type="date" id="datePicker" class="form-control">
                                            </div>
                                        </div> --}}
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
                                            <th>Brand Name</th>
                                            <th>Product Name</th>
                                            <th>Total Buy Price</th>
                                            <th>Total No. of Unit Per Cartoon</th>
                                            <th>Total No. of Cartoons</th>
                                            {{-- <th>Total Items</th>
                                            <th>Missing Items</th> --}}
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupedData as $data)
                                            <tr>
                                                <td>{{ $data['product_id'] }}</td>
                                                <td>{{ $data['brand_name'] }}</td>
                                                <td>{{ $data['product_name'] }}</td>
                                                <td>{{ $data['total_buy_price'] }}</td>
                                                <td>{{ $data['total_no_of_unit'] }}</td>
                                                <td>{{ $data['total_quantity'] }}</td>
                                                {{-- <td>
                                                    @php
                                                        $totalBuyPrice = 0;
                                                        foreach ($data['batches'] as $batch) {
                                                            $totalBuyPrice += $batch['buy_price'];
                                                        }
                                                    @endphp
                                                    {{ number_format($totalBuyPrice, 2) }}
                                                </td> --}}
                                                {{-- <td>{{ count($data['batches']) }}</td> --}}
                                                {{-- <td>
                                                    @php
                                                        $totalCartons = 0;
                                                        foreach ($data['batches'] as $batch) {
                                                            $totalCartons += $batch['cartons'];
                                                        }
                                                    @endphp
                                                    {{ $totalCartons }}
                                                </td> --}}
                                                {{-- <td>
                                                    @php
                                                        $totalItems = 0;
                                                        foreach ($data['batches'] as $batch) {
                                                            $totalItems += $batch['total_items'];
                                                        }
                                                    @endphp
                                                    {{ $totalItems }}
                                                </td> --}}
                                                {{-- <td>
                                                    @php
                                                        $missingItems = 0;
                                                        foreach ($data['batches'] as $batch) {
                                                            $missingItems += $batch['missing_items'];
                                                        }
                                                    @endphp
                                                    {{ $missingItems }}
                                                </td> --}}
                                                <td>
                                                    @php
                                                        $created_at = '';
                                                       
                                                        $formatted_date = date('Y-m-d', strtotime($data['created_at']));
                                                    @endphp
                                                    {{ $formatted_date }}
                                                </td>
                                                <td>
                                                    <!-- Action Buttons -->
                                                    <div>
                                                        @can('edit-purchase')
                                                            <a href="{{ route('stock.show', ['stock' => $data['product_id']]) }}"
                                                                class="btn btn-sm btn-warning edit-stock-btn"
                                                                data-id="{{ $data['product_id'] }}">
                                                                Edit
                                                            </a>
                                                        @endcan

                                                        @can('delete-purchase')
                                                            <button class="btn btn-sm btn-danger delete-stock-btn"
                                                                data-id="{{ $data['product_id'] }}">
                                                                Delete
                                                            </button>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
    $('.delete-stock-btn').click(function(e) {
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
                            '{{ route('stock.list') }}';
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                },
                error: function() {
                    toastr.error('Error occurred while deleting the record.');
                }
            });
        }
    });
</script>
</body>

</html>
