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
                                <h4 class="card-title mb-4">Order List</h4>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                           
                                            <th>Customer Name</th>
                                            <th>Customer Type</th>
                                            <th>Total Items</th>
                                            <th>Invoice Number</th>
                                            <th>Invoice Total</th>
                                            <th>Invoice Approved</th>
                                            {{-- <th>Actions</th>  --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            <tr>
                                               
                                                <td>{{ $order->customer }}</td>
                                                <td>{{ ucfirst($order->customer_type) }}</td>
                                                <td>{{ $order->total_items }}</td>
                                                <td>{{ $order->invoice_number ?? 'N/A' }}</td>
                                                <td>{{ $order->invoice_total ?? 'N/A' }}</td>
                                                
                                                 <td>{{ $order->invoice_approved ? 'Yes' : 'No' }}</td>
                                                {{-- <td>
                                                    <a href="" class="btn btn-primary">View Invoice</a>
                                                </td>  --}}
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

</body>

</html>
