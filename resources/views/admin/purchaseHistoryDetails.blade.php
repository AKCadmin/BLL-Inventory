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
                                <div class="container mt-4">
                                    <div class="card shadow">
                                        <div class="card-header bg-primary text-white">
                                            <h3 class="mb-0">Batch Details</h3>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Product Information</h5>
                                            <div class="row">
                                                {{-- <div class="col-md-6">
                                                    <p><strong>Name:</strong> {{ $batch['name'] ?? 'N/A' }}</p>
                                                    <p><strong>Description:</strong> {{ $batch['description'] ?? 'N/A' }}</p>
                                                    <p><strong>Status:</strong> {{ $batch['status'] ?? 'N/A' }}</p>
                                                </div> --}}
                                                <div class="col-md-6">
                                                    <p><strong>Batch Number:</strong> {{ $batch['batch_number'] ?? 'N/A' }}</p>
                                                    <p><strong>Manufacturing Date:</strong> {{ $batch['manufacturing_date'] ?? 'N/A' }}</p>
                                                    <p><strong>Expiry Date:</strong> {{ $batch['expiry_date'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>Base Price:</strong> {{ $batch['base_price'] ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Buy Price:</strong> {{ $batch['buy_price'] ?? 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Exchange Rate:</strong> {{ $batch['exchange_rate'] ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <p><strong>Notes:</strong> {{ $batch['notes'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="card shadow mt-4">
                                        <div class="card-header bg-secondary text-white">
                                            <h5 class="mb-0">Cartons</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped table-bordered">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>Carton Number</th>
                                                        <th>No of Items Inside</th>
                                                        <th>Missing Items</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($batch['cartons'] as $carton)
                                                        <tr>
                                                            <td>{{ $carton['carton_number'] }}</td>
                                                            <td>{{ $carton['no_of_items_inside'] }}</td>
                                                            <td>{{ $carton['missing_items'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
