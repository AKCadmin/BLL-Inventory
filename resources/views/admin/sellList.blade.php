@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Sell List']); ?>
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

                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Tables', 'title' => 'Sell List']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Sell List</h4>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>SKU</th>
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
                                        @foreach ($sells as $sell)
                                        @php
                                                // Check if the batch_no is present in the 'Sell' table
                                                $batchExists = \App\Models\Sell::where(
                                                    'batch_no',
                                                    $sell->batch_no,
                                                )->exists();
                                            @endphp
                                            <tr>
                                                <td>{{ $sell->id }}</td>
                                                <td>{{ $sell->sku }}</td>
                                                <td>{{ $sell->batch_no }}</td>
                                                <td>{{ $sell->hospital_price }}</td>
                                                <td>{{ $sell->wholesale_price }}</td>
                                                <td>{{ $sell->retail_price }}</td>
                                                <td>{{ $sell->valid_from }}</td>
                                                <td>{{ $sell->valid_to }}</td>
                                                <td>
                                                    @can('edit-sell')
                                                    <a href="{{ route('sell.edit', ['sell' => $sell->id]) }}" class="btn btn-sm btn-primary edit-sell-btn" data-id="{{ $sell->id }}" 
                                                        @if ($batchExists) style="pointer-events: none; opacity: 0.6;" @endif>Edit</a>
                                                    @endcan
                                                    @can('delete-sell')
                                                    <button class="btn btn-sm btn-danger delete-sell-btn" data-id="{{ $sell->id }}">Delete</button>
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
        // Delete button click event
        $('.delete-sell-btn').click(function(e) {
            e.preventDefault();
            var sellId = $(this).data('id');
            var row = $(this).closest('tr');

            if (confirm('Are you sure you want to delete this sell record?')) {
                $.ajax({
                    url: '/sell/' + sellId,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}", 
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            row.remove(); 
                            toastr.success(response.message);
                            window.location.href =
                            '{{ route('sell.list') }}'; 
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
    });
</script>
</body>

</html>
