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
                                <h5 class="card-title mb-4">Product Information</h5>

                                @foreach ($groupedData as $product)
                                    <div class="product-section mb-5">
                                        <h3 class="product-name">{{ $product['product_name'] }} <small>({{ $product['brand_name'] }})</small></h3>
                                        <p><strong>Status:</strong> {{ ucfirst($product['status']) }}</p>

                                        @foreach ($product['batches'] as $batch)
                                            <div class="batch-details card mt-4">
                                                <div class="card-header">
                                                    <h5 class="batch-title">Batch No: {{ $batch['batch_no'] }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Buy Price:</strong> {{ $batch['buy_price'] }}</p>
                                                    <p><strong>Manufacturing Date:</strong> {{ $batch['manufacturing_date'] }}</p>
                                                    <p><strong>Expiry Date:</strong> {{ $batch['expiry_date'] }}</p>
                                                    <p><strong>Base Price:</strong> {{ $batch['base_price'] }}</p>
                                                    <p><strong>Exchange Rate:</strong> {{ $batch['exchange_rate'] }}</p>
                                                    <p><strong>Notes:</strong> {{ $batch['notes'] }}</p>

                                                    <div class="cartons-list mt-3">
                                                        <h6>Cartons for Batch {{ $batch['batch_no'] }}</h6>
                                                        @forelse ($batch['cartons'] as $carton)
                                                            <div class="carton-item border rounded p-3 mb-3">
                                                                {{-- <p><strong>Carton ID:</strong> {{ $carton['carton_id'] }}</p> --}}
                                                                <p><strong>Items in Carton:</strong> {{ $carton['items_inside'] }}</p>
                                                                <p><strong>Missing Items in Carton:</strong> {{ $carton['missing_items'] }}</p>
                                                            </div>
                                                        @empty
                                                            <p class="text-muted">No cartons available for this batch.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>
</div>

@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')

</body>
</html>
