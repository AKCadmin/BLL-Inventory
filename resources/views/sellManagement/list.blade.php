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

                                <table id="stocktable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Customer Name</th>
                                            <th>Customer Type</th>
                                            <th>Total No of Cartoons Sale</th>
                                            <th>Price</th>
                                            <th>Order Id</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sellList as $index => $item)
                                        @php
                                            $customer = App\Models\Customer::where('id',$item->customer)->first();
                                          
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer->name }}</td> <!-- Replace with customer name if available -->
                                            <td>{{ ucfirst($item->customer_type) }}</td>
                                            <td>{{ $item->total_cartons }}</td>
                                            <td>{{ number_format($item->total_price, 2) }}</td>
                                            <td>{{ $item->order_id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->sale_date)->format('d-m-Y') }}</td>
                                            <td>
                                                
                                                <a href="{{ route('sellCounter.edit', $item->order_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="{{ route('sellCounter.destroy', $item->order_id) }}" class="btn btn-danger btn-sm">Delete</a>
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
