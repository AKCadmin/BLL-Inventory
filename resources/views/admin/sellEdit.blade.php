@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>


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

                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Tables', 'title' => 'Data Tables']); ?>
                <form action="{{ route('sell.updateSell', ['sell' => $sell->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="sellId" value="{{ old('id', $sell->id) }}">

                    <h4 class="mt-4">Update Sell</h4>

                    <!-- First Row: SKU and Batch No -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="skuSelect" class="form-label">SKU</label>
                            <select id="skuSelect" name="sku" class="form-select select2" required>
                                <option value="" disabled selected>SKU</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->sku }}"
                                        {{ $product->sku == old('sku', $sell->sku) ? 'selected' : '' }}>
                                        {{ $product->sku }} - {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6">
                            <label for="batchNoSelect" class="form-label">Batch No</label>
                            <select id="batchNoSelect" name="batch_no" class="form-select select2" required>
                                <option value="" disabled selected>Batch No</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->batch_number }}"
                                        {{ $batch->batch_number == old('batch_no', $sell->batch_no) ? 'selected' : '' }}>
                                        {{ $batch->batch_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Second Row: Hospital Price, Wholesale Price, Retail Price -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="hospitalPrice" class="form-label">Hospital Price</label>
                            <input type="number" step="0.01" class="form-control" id="hospitalPrice"
                                name="hospital_price" value="{{ old('hospital_price', $sell->hospital_price) }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label for="wholesalePrice" class="form-label">Wholesale Price</label>
                            <input type="number" step="0.01" class="form-control" id="wholesalePrice"
                                name="wholesale_price" value="{{ old('wholesale_price', $sell->wholesale_price) }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label for="retailPrice" class="form-label">Retail Price</label>
                            <input type="number" step="0.01" class="form-control" id="retailPrice"
                                name="retail_price" value="{{ old('retail_price', $sell->retail_price) }}" required>
                        </div>
                    </div>

                    <!-- Third Row: Valid From and Valid To -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="validFrom" class="form-label">Valid From</label>
                            <input type="date" class="form-control" id="validFrom" name="valid_from"
                                value="{{ old('valid_from', $sell->valid_from) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="validTo" class="form-label">Valid To</label>
                            <input type="date" class="form-control" id="validTo" name="valid_to"
                                value="{{ old('valid_to', $sell->valid_to) }}" required>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div>
                        <button type="submit" class="btn btn-primary">Update Sell Record</button>
                    </div>
                </form>



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
<script src="assets/js/customJs/productManagement.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option", // Placeholder text
            allowClear: true // Option to clear selection
        });
    });
</script>
<script>
    $(document).ready(function() {

        function loadProducts() {
            ajaxRequest('{{ route('product.getData') }}', 'GET', {},
                function(response) {
                    var products = response.products;
                    var skuSelect = $('#skuSelect'); // Get the SKU select element

                    // Clear any existing options
                    skuSelect.empty();

                    // Add a default option
                    skuSelect.append('<option value="" disabled selected>SKU</option>');

                    // Loop through products and add them as options
                    products.forEach(function(product) {
                        skuSelect.append('<option value="' + product.sku + '">' + product.sku +
                            ' - ' + product.name + '</option>');
                    });
                    skuSelect.select2();

                }
            );
        }

        function loadBatches(sku) {

            $.ajax({
                url: '{{ url('/batches') }}/' + sku,
                type: 'GET',
                success: function(response) {
                    var batchSelect = $('#batchNoSelect');
                    batchSelect.empty();

                    // Add a default "select" option
                    batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    // Check if batches exist in the response
                    if (response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            batchSelect.append('<option value="' + batch.batch_number +
                                '">' + batch.batch_number + '</option>');
                        });
                    } else {
                        batchSelect.append(
                            '<option value="" disabled>No batches available</option>');
                    }

                    batchSelect.select2({
                        placeholder: "Select a Batch",
                        allowClear: true
                    });
                },
                error: function() {
                    alert("Error loading batches.");
                }
            });
        }

        $('#skuSelect').on('change', function() {
            var selectedSku = $(this).val();


            if (selectedSku) {
                loadBatches(selectedSku);
            } else {
                $('#batchNoSelect').empty().append(
                    '<option value="" disabled selected>Batch No</option>');
            }
        });

        $('form').on('submit', function(e) {
            e.preventDefault();

            // Collect form data
            var formData = {
                sku: $('#skuSelect').val(),
                batch_no: $('#batchNoSelect').val(),
                hospital_price: $('#hospitalPrice').val(),
                wholesale_price: $('#wholesalePrice').val(),
                retail_price: $('#retailPrice').val(),
                valid_from: $('#validFrom').val(),
                valid_to: $('#validTo').val(),
            };

            var sellId = $('#sellId').val();

            $.ajax({
                url: "{{ route('sell.updateSell', ['sell' => ':id']) }}".replace(':id',
                    sellId),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Sell updated successfully!');

                        window.location.href =
                            '{{ route('sell.list') }}';
                    } else {
                        toastr.error('Failed to update sell. Please try again.');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display each validation error using toastr
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            if (errors[field]) {
                                errors[field].forEach(errorMessage => {
                                    toastr.error(errorMessage);
                                });
                            }
                        }
                    } else {
                        toastr.error('An error occurred while processing the request.');
                    }
                }
            });
        });

    });
</script>

<script>
    @if ((isset($showModal) && $showModal) || $errors->any() || session('success'))
        modal.style.display = "block";
    @endif
</script>

</body>

</html>
