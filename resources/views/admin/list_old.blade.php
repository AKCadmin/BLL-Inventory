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
                                {{-- <div class="row mb-3">
                                    <div class="col-md-4 col-sm-6 col-12">
                                        <div class="input-group">
                                            <select id="sku-filter" class="form-control custom-select">
                                                <option value="">Select SKU</option>
                                                @foreach ($stocks->unique('sku') as $stock)
                                                    <option value="{{ $stock->sku }}">{{ $stock->sku }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}

                                <table id="stocktable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            {{-- <th>SKU</th> --}}
                                            <th>Batch No</th>
                                            <th>Buy Price</th>
                                            <th>No. of Carton</th>
                                            <th>Total Item</th>
                                            <th>Missing Item</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                {{-- <td>{{ $stock->sku }}</td> --}}
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
    $(document).ready(function() {
        $('#organization-filter').prop('disabled', true).css('background-color', '#e0e0e0');
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
