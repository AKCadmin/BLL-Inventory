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

                               
                                    <div class="product-section mb-5">
                                        <h3 class="product-name">{{ $brand->name }}</h3>
                                        <p><strong>Status:</strong> {{ ucfirst($product->status) }}</p>

                                        @foreach($data as $index => $item)
                                            <div class="batch-details card mt-4">
                                                <div class="card-header">
                                                    <h5 class="batch-title">Batch No: {{ $item->batch_number }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Buy Price:</strong> {{ $item->buy_price }}</p>
                                                    <p><strong>Manufacturing Date:</strong> {{ $item->manufacturing_date }}</p>
                                                    <p><strong>Expiry Date:</strong> {{ $item->expiry_date }}</p>
                                                    <p><strong>Base Price:</strong> {{ $item->base_price }}</p>
                                                    <p><strong>Exchange Rate:</strong> {{ $item->exchange_rate }}</p>
                                                    {{-- <p><strong>Notes:</strong> {{ $item->notes }}</p> --}}

        
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                               
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

<script>
    $(document).ready(function() {
        $('#organization-filter').hide();
    })
</script>
</body>
</html>
