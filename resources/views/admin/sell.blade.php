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
                <form>
                    <h4 class="mt-4">Add Sell</h4>

                    <!-- First Row: SKU and Batch No -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="skuSelect" class="form-label">SKU</label>
                            <select id="skuSelect" class="form-select select2">
                                <option value="" disabled selected>SKU</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="batchNoSelect" class="form-label">Batch No</label>
                            <select id="batchNoSelect" class="form-select select2">
                                <option value="" disabled selected>Batch No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Second Row: Hospital Price, Wholesale Price, Retail Price -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="hospitalPrice" class="form-label">Hospital Price</label>
                            <input type="text" id="hospitalPrice" class="form-control price-input">
                        </div>
                        <div class="col-md-4">
                            <label for="wholesalePrice" class="form-label">Wholesale Price</label>
                            <input type="text" id="wholesalePrice" class="form-control price-input">
                        </div>
                        <div class="col-md-4">
                            <label for="retailPrice" class="form-label">Retail Price</label>
                            <input type="text" id="retailPrice" class="form-control price-input">
                        </div>
                    </div>

                    <!-- Third Row: Valid From and Valid To -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="validFrom" class="form-label">Valid From</label>
                            <input type="date" id="validFrom" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="validTo" class="form-label">Valid To</label>
                            <input type="date" id="validTo" class="form-control">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div>
                        <button type="submit" class="btn btn-primary">Submit</button>
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
            placeholder: "Select an option",
            allowClear: true 
        });
      
        $('#validFrom').on('change', function () {
            const validFrom = $(this).val();
            if (validFrom) {
                $('#validTo').attr('min', validFrom);
            } else {
                $('#validTo').removeAttr('min'); 
            }
        });

        // Restrict price inputs to numbers only
        $('.price-input').on('input', function () {
            this.value = this.value.replace(/[^0-9.]/g, '');
        });

        function loadProducts() {
            ajaxRequest('{{ route('product.getData') }}', 'GET', {},
                function(response) {
                    var products = response.products;
                    var skuSelect = $('#skuSelect');

                    skuSelect.empty();
                    skuSelect.append('<option value="" disabled selected>SKU</option>');

                   
                    products.forEach(function(product) {
                        skuSelect.append('<option value="' + product.sku + '">' + product.sku +
                            ' - ' + product.name + '</option>');
                    });

                }
            );
        }

        function loadBatches(sku) {
           
            $.ajax({
                url: '{{ url('/sell/batches') }}/' + sku,
                type: 'GET',
                success: function(response) {
                    var batchSelect = $('#batchNoSelect');
                    batchSelect.empty(); 

                    batchSelect.append('<option value="" disabled selected>Batch No</option>');

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

        loadProducts();

        $('#skuSelect').on('change', function() {
            var selectedSku = $(this).val(); // Get the selected SKU

            // Check if an SKU is selected
            if (selectedSku) {
                loadBatches(selectedSku); // Fetch the batches for the selected SKU
            } else {
                $('#batchNoSelect').empty().append(
                    '<option value="" disabled selected>Batch No</option>'); // Clear batch options
            }
        });

        $('form').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                sku: $('#skuSelect').val(),
                batch_no: $('#batchNoSelect').val(),
                hospital_price: $('#hospitalPrice').val(),
                wholesale_price: $('#wholesalePrice').val(),
                retail_price: $('#retailPrice').val(),
                valid_from: $('#validFrom').val(),
                valid_to: $('#validTo').val(),
            };

            // Send AJAX request
            $.ajax({
                url: '{{ route('sell.store') }}', 
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Sell added successfully!');

                        $('form')[0].reset();
                    } else {
                        toastr.error('Failed to add sell. Please try again.');
                    }
                },
                error: function() {
                    toastr.error('An error occurred while processing the request.');
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
