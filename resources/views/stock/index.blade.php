@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>


    @include('partials.link')
    @include('partials.head-css')

    <style>
        .rowTemplate {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .rowTemplate .form-control {
            height: 38px;
            /* Ensure uniform height */
        }

        .removeRow {
            width: 100%;
            max-width: 120px;
        }

        #addRow {
            margin-top: 10px;
        }
    </style>
</head>

@include('partials.body')


<!-- Begin page -->
<div id="layout-wrapper">

    @include('partials.topbar')
    @include('partials.sidebar')
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <div class="container my-5">

                    <h1 class="text-center mb-4">Add Stock</h1>
                    <form action="{{ route('stock.store') }}" method="post">
                        @csrf
                        <div class="row align-items-center">

                            @if(auth()->user()->role == 1)
                            <div class="col-md-6 mb-3">
                            <label for="autoSizingSelect">Select Organization</label>
                            
                                <select id="organizationName" name="organizationName" class="form-control custom-select">
                                    <option value="">Select Organization</option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->name }}">{{ str_replace('_', ' ', $organization->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label for="autoSizingSelect">Select Supplier</label>
                                <select class="form-select brand" id="brandId" id="autoSizingSelect" name="brand_id">
                                    <option value="">Select Supplier &ensp;</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Select Product -->
                            <div class="col-md-6 mb-3">
                                <label for="selectSku" class="form-label">Select Product</label>
                                <select id="SKU" name="SKU" class="form-select select2">
                                    <option selected disabled>Select Product </option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="unit" class="form-label">Unit</label>
                                <input type="text" name="unit" id="unit" class="form-control unit" readonly
                                    style="background-color: #e9ecef; cursor: not-allowed;">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Purchase Type</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="internalPurchase"
                                        name="internal_purchase">
                                    <label class="form-check-label" for="internalPurchase">Internal Purchase</label>
                                </div>
                            </div>

                            <div id="customerDiv" class="hidden col-md-4" style="display: none">
                                <label class="block text-sm font-medium mb-2">Select Customer</label>
                                <select name="customer" id="customer" class="form-select w-full rounded-lg border p-2">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="invoice" class="form-label">Invoice</label>
                                <input type="text" name="invoice" id="invoice" class="form-control invoice">
                            </div>
                        </div>

                        <!-- Add Purchase -->
                        <h4>Add Purchase</h4>


                        <!-- Dynamic Rows for Batch Info -->
                        <div id="batchRows" class="container mb-3">
                            <div class="row rowTemplate border p-3 rounded mb-2">
                                <div class="col-md-4 mb-2">
                                    <label for="batchNo" class="form-label">Batch No.</label>
                                    <input type="text" name="batchNo" id="batchNo" class="form-control batchNo"
                                        placeholder="Enter Batch No.">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="manufacturingDate" class="form-label">Manufacturing
                                        Date</label>
                                    <input type="date" name="manufacturingDate" id="manufacturingDate"
                                        class="form-control manufacturingDate">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="expiryDate" class="form-label">Expiry Date</label>
                                    <input type="date" name="expiryDate" id="expiryDate"
                                        class="form-control expiryDate">
                                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="basePrice" class="form-label">Base Price</label>
                                    <input type="text" name="basePrice" id="basePrice" class="form-control basePrice"
                                        placeholder="Enter Base Price">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="exchangeRate" class="form-label">Exchange Rate</label>
                                    <input type="text" name="exchangeRate" id="exchangeRate"
                                        class="form-control exchangeRate" placeholder="Enter Exchange Rate">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="buyPrice" class="form-label">Buy Price</label>
                                    <input type="text" name="buyPrice" id="buyPrice"
                                        class="form-control buyPrice" placeholder="Enter Buy Price">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="noOfUnits" class="form-label">No of Item Per Cartoon</label>
                                    <input type="number" name="noOfUnits" id="noOfUnits"
                                        class="form-control noOfUnits" placeholder="Enter No of Units">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="qty" class="form-label">Total No of Cartoons</label>
                                    <input type="number" name="qty" id="qty" class="form-control qty"
                                        placeholder="Enter No of Cartoon">
                                </div>

                                {{-- <div class="col-md-4 mb-2">
                                    <label for="noOfCartons" class="form-label">No Of Cartons</label>
                                    <input type="number" name="noOfCartons" id="noOfCartons"
                                        class="form-control noOfCartons" placeholder="Enter No Of Cartons">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="button" name="processCartons" id="processCartons"
                                        class="btn btn-info mt-3 processCartons">Process
                                        Cartons</button>
                                </div>
                                <div id="cartonRows" class="container mb-3"> --}}

                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="notes" class="form-label">Notes</label>
                                <input type="text" name="notes" id="notes" class="form-control notes"
                                    placeholder="Enter Notes">
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-danger removeRow">Remove</button>
                            </div>
                        </div>
                </div>


                <div class="d-flex gap-2">
                    <button type="button" id="addRow" class="btn btn-primary">Add Row</button>

                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success mt-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')
<script src="assets/js/customJs/userManagement.js"></script>
<Script>
    $(document).ready(function() {
        $('#organization-filter').hide();
        $('.basePrice, .exchangeRate, .buyPrice, .noOfCartons, .missingItems, .itemsInside', ).on('input',
            function() {
                $(this).val($(this).val().replace(/[^0-9.]/g, ''));
            });

        $('.basePrice, .exchangeRate').on('input', function() {
            $(this).val($(this).val().replace(/[^0-9.]/g, ''));
            var basePrice = parseFloat($('.basePrice').val()) || 0;
            var exchangeRate = parseFloat($('.exchangeRate').val()) || 0;
            var buyPrice = basePrice * exchangeRate;
            $('.buyPrice').val(buyPrice.toFixed(2));
        });

        var currentDate = new Date().toISOString().split('T')[0];

        $('.manufacturingDate').attr('max', currentDate);
        $('.manufacturingDate').on('change', function() {
            var manufacturingDate = $(this).val();
            $(this).closest('.rowTemplate').find('.expiryDate').attr('min', manufacturingDate);
        });

        $('.expiryDate').on('change', function() {
            var manufacturingDate = $(this).closest('.rowTemplate').find('.manufacturingDate').val();
            var expiryDate = $(this).val();

            if (expiryDate && expiryDate < manufacturingDate) {
                alert("Expiry Date cannot be before Manufacturing Date.");
                $(this).val("");
            }
        });

        $('#brandId').on('change', function() {
            let brandId = $(this).val();
            $('#unit').val("");
            let url = `{{ route('product.getDataById') }}`;
            ajaxRequest(url, 'GET', {
                    brandId
                },
                function(response) {
                    console.log(response, "response")
                    if (response.data && response.data.length > 0) {
                        $('#SKU').html('<option selected disabled>Select Product</option>');
                        $.each(response.data, function(index, product) {
                            $('#SKU').append(
                                `<option value="${product.id}">${product.name}</option>`
                            );
                        });
                    }
                }
            );
        });

        $('#SKU').on('change', function() {
            let productId = $(this).val();

            let url = `{{ route('product.getDataById') }}`;
            ajaxRequest(url, 'GET', {
                    productId
                },
                function(response) {
                    $('#unit').val(response?.data?.unit)
                }
            );
        });


        $('form').on('submit', function(e) {

            e.preventDefault();

            var formData = new FormData(this);


            $('#batchRows .rowTemplate').each(function(index) {
                var batchData = {
                    batchNo: $(this).find('.batchNo').val(),
                    manufacturingDate: $(this).find('.manufacturingDate').val(),
                    expiryDate: $(this).find('.expiryDate').val(),
                    basePrice: $(this).find('.basePrice').val(),
                    exchangeRate: $(this).find('.exchangeRate').val(),
                    buyPrice: $(this).find('.buyPrice').val(),
                    noOfUnits: $(this).find('.noOfUnits').val(),
                    qty: $(this).find('.qty').val(),
                    notes: $(this).find('.notes').val(),
                    noOfCartons: $(this).find('.noOfCartons').val(),
                    cartons: []
                };

                // $(this).find('.cartonRow').each(function(cartonIndex) {
                //     var cartonData = {
                //         itemsInside: $(this).find('.itemsInside').val(),
                //         missingItems: $(this).find('.missingItems').val()
                //     };
                //     batchData.cartons.push(cartonData);
                // });
                console.log(batchData, "batchData2");

                formData.append('batches[' + index + ']', JSON.stringify(batchData));
            });
            console.log("Inspecting FormData:");
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            const form = event.target;
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Form submitted successfully!');
                        let user = "{{auth()->user()->role}}";
                        if(user == 1){
                        window.location.href =
                            '{{ route('stock.list') }}';
                        }else{
                            window.location.href =
                            '{{ route('purchase.list') }}';
                        }
                    } else {
                        toastr.error('Submission failed: ' + data.message);
                    }
                })
                .catch(error => {
                    toastr.error('An error occurred: ' + error.message);
                });
        });

        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        $('#internalPurchase').change(function() {
            const customerDiv = $('#customerDiv');
            const customerSelect = $('#customer');

            if ($(this).is(':checked')) {
                customerDiv.show();
                customerSelect.html('<option selected disabled>Loading Customers...</option>');

                const url = `{{ route('retail.customers.list') }}`;
                ajaxRequest(url, 'GET', {}, function(response) {
                    console.log(response, "response");

                    if (response.customers && response.customers.length > 0) {
                        customerSelect.html(
                            '<option selected disabled>Select Purchase Customer</option>');
                        $.each(response.customers, function(index, customer) {
                            customerSelect.append(
                                `<option value="${customer.id}">${customer.name || 'Unnamed Customer'}</option>`
                            );
                        });
                    } else {
                        customerSelect.html('<option disabled>No Customers Found</option>');
                    }
                }, function(error) {
                    console.error('Error fetching customers:', error);
                    customerSelect.html('<option disabled>Error Loading Customers</option>');
                });
            } else {
                customerDiv.hide();
                customerSelect.html('<option selected disabled>Select Purchase Customer</option>');
            }
        });


        // let url = `{{ route('product.getData') }}`;
        // ajaxRequest(url, 'GET', {},
        //     function(response) {
        //         if (response.products && response.products.length > 0) {
        //             $('#SKU').html('<option selected disabled>Select Product</option>');
        //             $.each(response.products, function(index, product) {
        //                 $('#SKU').append(
        //                     `<option value="${product.id}">${product.name}</option>`
        //                 );
        //             });
        //         }
        //     }
        // );
        $('#addRow').click(function() {
            var newBatchRow = $(`
            <div class="row rowTemplate border p-3 rounded mb-2">
                <div class="col-md-4 mb-2">
                    <label for="batchNo" class="form-label">Batch No.</label>
                    <input type="text" class="form-control batchNo" placeholder="Enter Batch No.">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="manufacturingDate" class="form-label">Manufacturing Date</label>
                    <input type="date" class="form-control manufacturingDate">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="expiryDate" class="form-label">Expiry Date</label>
                    <input type="date" class="form-control expiryDate">
                </div>

                <div class="col-md-4 mb-2">
                    <label for="basePrice" class="form-label">Base Price</label>
                    <input type="text" class="form-control basePrice" placeholder="Enter Base Price">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="exchangeRate" class="form-label">Exchange Rate</label>
                    <input type="text" class="form-control exchangeRate" placeholder="Enter Exchange Rate">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="buyPrice" class="form-label">Buy Price</label>
                    <input type="text" class="form-control buyPrice" placeholder="Enter Buy Price">
                </div>

                                <div class="col-md-4 mb-2">
                                    <label for="noOfUnits" class="form-label">No of Item Per Cartoon</label>
                                    <input type="number" name="noOfUnits" id="noOfUnits" class="form-control noOfUnits"
                                        placeholder="Enter No of Units">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label for="qty" class="form-label">Total No of Cartoons</label>
                                    <input type="number" name="qty" id="qty" class="form-control qty"
                                        placeholder="Enter No of Cartoon">
                                </div>
                                <div class="col-md-4 mb-2">
                                <label for="notes" class="form-label">Notes</label>
                                <input type="text" name="notes" id="notes" class="form-control notes"
                                    placeholder="Enter Notes">
                            </div>
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-danger removeRow">Remove</button>
                </div>
            </div>
        `);

            $('#batchRows').append(newBatchRow);


            newBatchRow.find('.manufacturingDate').attr('max', currentDate);


            newBatchRow.find('.manufacturingDate').on('change', function() {
                var manufacturingDate = $(this).val();
                $(this).closest('.rowTemplate').find('.expiryDate').attr('min',
                    manufacturingDate);
            });


            newBatchRow.find('.expiryDate').on('change', function() {
                var manufacturingDate = $(this).closest('.rowTemplate').find(
                    '.manufacturingDate').val();
                var expiryDate = $(this).val();

                if (expiryDate && expiryDate < manufacturingDate) {
                    alert("Expiry Date cannot be before Manufacturing Date.");
                    $(this).val("");
                }
            });

            newBatchRow.find('.processCartons').click(function() {
                var noOfCartons = newBatchRow.find('.noOfCartons').val();
                var cartonRowsContainer = newBatchRow.find('#cartonRows');
                cartonRowsContainer.empty();

                if (noOfCartons && noOfCartons > 0) {

                    for (var i = 0; i < noOfCartons; i++) {
                        var cartonRow = $(`
                        <div class="row cartonRow mb-2">
                            <div class="col-md-4 mb-2">
                                <label for="itemsInside_${i+1}" class="form-label">No. of Items Inside (Carton ${i+1})</label>
                                <input type="number" id="itemsInside_${i+1}" class="form-control itemsInside" placeholder="Enter Items Inside for Carton ${i+1}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="missingItems_${i+1}" class="form-label">Missing Items (Carton ${i+1})</label>
                                <input type="number" id="missingItems_${i+1}" class="form-control missingItems" placeholder="Enter Missing Items for Carton ${i+1}">
                            </div>

                        </div>
                    `);
                        cartonRowsContainer.append(
                            cartonRow);
                    }
                } else {
                    alert('Please enter a valid number of cartons.');
                }
            });

            newBatchRow.find('.removeRow').click(function() {
                newBatchRow.remove();
            });

            newBatchRow.on('click', '.removeCartonRow', function() {
                $(this).closest('.cartonRow').remove();
            });

            newBatchRow.on('input',
                '.basePrice, .exchangeRate, .buyPrice, .noOfCartons, .missingItems, .itemsInside',
                function() {
                    $(this).val($(this).val().replace(/[^0-9.]/g, ''));
                });

            newBatchRow.on('input', '.basePrice, .exchangeRate', function() {
                var basePrice = parseFloat(newBatchRow.find('.basePrice').val()) || 0;
                var exchangeRate = parseFloat(newBatchRow.find('.exchangeRate').val()) || 0;
                var buyPrice = basePrice * exchangeRate;
                newBatchRow.find('.buyPrice').val(buyPrice.toFixed(2));
            });
        });
    });


    $(document).ready(function() {
        $('.processCartons').click(function() {
            var noOfCartons = $('#noOfCartons').val();

            if (noOfCartons && noOfCartons > 0) {
                $('#cartonRows').empty();
                for (var i = 0; i < noOfCartons; i++) {
                    var newRow = $(`
                        <div class="row cartonRow mb-2">
                            <div class="col-md-4 mb-2">
                                <label for="itemsInside_${i+1}" class="form-label">No. of Items Inside (Carton ${i+1})</label>
                                <input type="number" id="itemsInside_${i+1}" class="form-control itemsInside" placeholder="Enter Items Inside for Carton ${i+1}">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="missingItems_${i+1}" class="form-label">Missing Items (Carton ${i+1})</label>
                                <input type="number" id="missingItems_${i+1}" class="form-control missingItems" placeholder="Enter Missing Items for Carton ${i+1}">
                            </div>
                           
                        </div>
                `);
                    $('#cartonRows').append(newRow);
                }
            } else {
                alert('Please enter a valid number of cartons.');
            }
        });

        // Remove row logic
        $(document).on('click', '.removeRow', function() {
            $(this).closest('.rowTemplate').remove();
        });
    });
</Script>

</body>

</html>
