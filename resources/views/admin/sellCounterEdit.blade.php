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
                <form id="productForm">
                    <input type="hidden" value="{{ $orderId }}" name="orderId" id="orderId">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Purchase Type</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="internalPurchase"
                                    name="internal_purchase">
                                <label class="form-check-label" for="internalPurchase">Internal Sale</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="paymentStatus" class="form-label">Payment Status</label>
                            <select class="form-select" id="paymentStatus" name="payment_status" required>
                                <option value="" disabled selected>Select Payment Status</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="customer" class="form-label">Select Customer</label>
                                <select id="customer" name="customer" class="form-select select2 customer sku-input">
                                    <option selected disabled>Select Customer</option>
                                </select>
                            </div>


                            <div class="mb-3">

                            </div>
                        </div>


                        <!-- SKU and Batch in one column -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label for="customerType" class="form-label">Select Customer Type</label>
                                <input type="hidden" id="customerType" class="customer-type">
                                <input type="text" disabled readonly class="form-control" id="customerTypeName">

                            </div>
                            <div class="mb-3">
                            </div>
                        </div>
                    </div>
                    <div id="skuRows">
                        <div class="skuRow">
                            <div class="row">
                                <!-- Customer and Customer Type in one column -->

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="customer" class="form-label">Select Customer</label>
                                        <select id="customer" name="customer"
                                            class="form-select select2 customer sku-input">
                                            <option selected disabled>Select Customer</option>
                                        </select>
                                    </div>


                                    <div class="mb-3">
                                        <label for="selectSku" class="form-label">Select Product</label>
                                        <select id="SKU" name="SKU" class="form-select select2 SKU sku-input">
                                            <option selected disabled>Select Product</option>
                                        </select>
                                    </div>
                                </div>


                                <!-- SKU and Batch in one column -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="customerType" class="form-label">Select Customer Type</label>
                                        <input type="hidden" id="customerType" class="customer-type">
                                        <input type="text" class="form-control" id="customerTypeName">
                                        {{-- <select class="form-select customer-type" id="customerType" required> --}}
                                        {{-- <option value="" disabled selected>Select customer type</option> --}}
                                        {{-- <option value="hospital">hospital</option>
                                            <option value="wholesale">wholesaler</option>
                                            <option value="retailer">retailer</option> --}}
                                        {{-- </select> --}}
                                    </div>



                                    <div class="mb-3 batch-row">
                                        <label for="batchNoSelect" class="form-label">Batch No</label>
                                        <select id="batchNoSelect"
                                            class="form-select select2 batchNoSelect batch-input">
                                            <option value="" disabled selected>Batch No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="saleDate" class="form-label">Sale Date</label>
                                    <input type="date" class="form-control" id="saleDate" name="sale_date"
                                        max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="unitsPerCarton">Number of Item Per
                                            Carton</label>
                                        <input type="number" class="form-control" id="unitsPerCarton"
                                            name="unitsPerCarton" readonly placeholder="Enter units per carton"
                                            disabled>
                                    </div>
                                    <div class="mb-3">

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="availableQtyCarton">Available Total No of
                                            Carton</label>
                                        <input type="number" class="form-control" readonly id="availableQtyCarton"
                                            name="availableQtyCarton" placeholder="Enter available no of carton"
                                            disabled>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Packaging Type</label>
                                        <div class="form-check">
                                            <input class="form-check-input packaging-type byCarton" type="checkbox"
                                                id="byCarton" name="packagingType" value="byCarton">
                                            <label class="form-check-label" for="byCarton">
                                                By Carton
                                            </label>
                                            <div id="quantityBox" style="display: none; width:20%; margin-top: 10px;">
                                                <label class="form-label" for="quantity">Enter Number of
                                                    Carton</label>
                                                <input type="number" class="form-control" id="quantity"
                                                    name="quantity" placeholder="Enter carton">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary add-row">Add More</button>
                    <button type="submit" id="sellSubmit" class="btn btn-primary">Submit</button>
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
        // const responseData = [{
        //         "customer": "2",
        //         "customerType": "2",
        //         "customerTypeName": "wholesale (10000.00)",
        //         "rowIndex": 0,
        //         "sku": "2",
        //         "batchNo": "S9879",
        //         "unitsPerCarton": "20",
        //         "availableQtyCarton": "10",
        //         "packagingType": {
        //             "byCarton": true,
        //             "quantity": "5"
        //         }
        //     },
        //     {
        //         "customer": "2",
        //         "customerType": "2",
        //         "customerTypeName": "wholesale (10000.00)",
        //         "rowIndex": 1,
        //         "sku": "3",
        //         "batchNo": "T9090",
        //         "unitsPerCarton": "10",
        //         "availableQtyCarton": "5",
        //         "packagingType": {
        //             "byCarton": true,
        //             "quantity": "3"
        //         }
        //     }
        // ];
        $('#organization-filter').hide();

        var company = $('#organization-filter').val(); // Get selected value
        console.log(company, "orga")
        customerList(company);
        const responseData = @json($responseData);
        console.log(responseData, "responseData")

        if (responseData && responseData.length > 0 && responseData[0].paymentStatus) {
            $('#paymentStatus').val(responseData[0].paymentStatus);
        }

        function customerList(company) {
            let url = `{{ route('customers.list') }}`;
            ajaxRequest(url, 'GET', {
                company
            }, function(response) {
                console.log(response, "response");
                if (response.customers && response.customers.length > 0) {
                    $('.customer').html('<option selected disabled>Select Product Customer</option>');
                    $.each(response.customers, function(index, customer) {
                        $('.customer').append(
                            `<option value="${customer.id}">${customer.name}</option>`
                        );
                    });
                    $('#customer').val(responseData[0].customer);
                    $('#customerType').val(responseData[0].customerType);
                    $('#customerTypeName').val(responseData[0].customerTypeName);

                    if (responseData[0].paymentStatus) {
                        $('#paymentStatus').val(responseData[0].paymentStatus);
                    }
                    populateForm(responseData);
                }
            });
        }

        function populateForm(data) {
            $('#skuRows').empty();
            data.forEach((item, index) => {
                console.log(item, index, "item index")
                let row = $(
                    `<div class="skuRow">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                               
                            </div>
                             <div class="mb-3">
                                <label for="SKU${index}" class="form-label">Select Product</label>
                                <select id="SKU_${index}" name="SKU" class="form-select select2 SKU sku-input">
                                    <option selected disabled>Select Product</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                               
                            </div>
                            <div class="mb-3 batch-row">
                                <label for="batchNoSelect${index}" class="form-label">Batch No</label>
                                <select id="batchNoSelect_${index}" class="form-select select2 batchNoSelect batch-input">
                                    <option value="${item.batchId}" selected>${item.batchNo}</option>
                                </select>
                            </div>                         
                        </div>

                  <div class="col-md-6">
                    <div class="mb-3">
                        <label for="saleDate${index}" class="form-label">Sale Date</label>
                        <input type="date" class="form-control saleDate" id="saleDate_${index}" name="saleDate" 
                               max="{{ date('Y-m-d') }}" value="${item.date ? item.date.split(' ')[0] : '{{ date('Y-m-d') }}'}">
                    </div>
                </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="unitsPerCarton${index}">Number of Unit Per Carton</label>
                                <input type="number" class="form-control" id="unitsPerCarton_${index}" name="unitsPerCarton" disabled value="${item.unitsPerCarton}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="availableQtyCarton${index}">Available Total No of Carton</label>
                                <input type="number" class="form-control" id="availableQtyCarton_${index}" disabled name="availableQtyCarton" value="${item.availableQtyCarton}" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Select Packaging Type</label>
                                <div class="form-check">
                                    <input class="form-check-input packaging-type byCarton" type="checkbox" id="byCarton${index}" name="packagingType" value="byCarton" ${item.packagingType.byCarton ? 'checked' : ''}>
                                    <label class="form-check-label" for="byCarton${index}">By Carton</label>
                                    <div id="quantityBox${index}" style="${item.packagingType.byCarton ? '' : 'display: none;'}; width:20%; margin-top: 10px;">
                                        <label class="form-label" for="quantity${index}">Enter Number of Carton</label>
                                        <input type="number" class="form-control" id="quantity_${index}" name="quantity" value="${item.packagingType.quantity}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
                );

                $('#skuRows').append(row);
                $(`#SKU_${index}`).select2({
                    placeholder: "Select a Product",
                    allowClear: true
                }).on('select2:select', function() { // Use Select2's select event
                    const selectedProductId = $(this).val();
                    const $batchSelect = $(`#batchNoSelect${index}`);
                    $batchSelect.empty();
                    $batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    // if (selectedProductId) {
                    //     // Get the selected product's SKU code
                    //     // const selectedProduct = response.products.find(p => p?.product[0]?.id ==
                    //     //     selectedProductId);
                    //     // if (selectedProduct && selectedProduct.sku) { // Make sure sku exists
                    //     //     loadBatches(selectedProduct.sku, index);
                    //     // } else {
                    //     //     console.error("SKU not found for product ID:", selectedProductId);
                    //     //     $batchSelect.append(
                    //     //         '<option value="" disabled>No batches available</option>');
                    //     //     $batchSelect.select2({
                    //     //         placeholder: "Select a Batch",
                    //     //         allowClear: true
                    //     //     });
                    //     // }

                    // } else {
                    //     $batchSelect.append(
                    //         '<option value="" disabled>No batches available</option>');
                    //     $batchSelect.select2({
                    //         placeholder: "Select a Batch",
                    //         allowClear: true
                    //     });
                    // }
                });


                GetSKU(index, item.sku, item.batchNo); // Pass initial batchNo
                // Set customer dropdown value
                $(`#customer${index}`).val(item.customer);

                $(document).on('change', `#batchNoSelect${index}`, function() {
                    var selectedBatch = $(this).val();
                    const rowIndex = index; // Get row index
                    console.log(selectedBatch, rowIndex, "woeooe")

                    if (selectedBatch) {
                        loadBatchData(selectedBatch,
                            rowIndex); // Pass rowIndex to loadBatchData
                    } else {
                        $(`#unitsPerCarton${rowIndex}`).val(''); // Clear if no batch
                        $(`#availableQtyCarton${rowIndex}`).val('');
                    }
                });
            });
        }

        function GetSKU(rowIndex, selectedProductId, selectedBatchNo) {
            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {}, function(res) {
                response = res; // Assign the response to the outer scope variable
                var productData = response.products;
                if (response.products) {
                    const $select = $(`#SKU_${rowIndex}`);
                    $select.html('<option selected disabled>Select Product</option>');
                    $.each(response.products, function(index, product) {
                        const productId = product?.product[0]?.id;
                        const productName = product?.product[0].name;
                        $select.append(
                            `<option value="${productId}" data-sku="${product.sku}">${productName}</option>` // Add data-sku
                        );
                    });

                    if (selectedProductId) {
                        $select.val(selectedProductId);
                        // Trigger the change event to load batches for the initial product
                        $select.trigger('change');
                    }

                    $(`#SKU${rowIndex}`).on('change', function() {
                        const selectedProductId = $(this).val();
                        console.log(selectedProductId, "selectedProductId")

                        loadBatches(selectedProductId, rowIndex);

                    });
                }
            });
        }

        function loadBatches(sku, rowIndex, selectedBatchNo = null) {
            $.ajax({
                url: '{{ url('/sellcounter/batches') }}/' + sku, // Use the SKU code here
                type: 'GET',
                success: function(response) {
                    const $batchSelect = $(`#batchNoSelect${rowIndex}`);
                    $batchSelect.empty();
                    $batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    if (response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            console.log(batch, "batch yaa find error")
                            $batchSelect.append(
                                `<option value="${batch.batch_no}">${batch.batch_no}</option>`
                            );
                        });
                    } else {
                        $batchSelect.append(
                            '<option value="" disabled>No batches available</option>');
                    }

                    $batchSelect.select2({
                        placeholder: "Select a Batch",
                        allowClear: true
                    }).on('select2:open',
                        function() { // Select2 open event to set initial value
                            if (selectedBatchNo) {
                                $batchSelect.val(selectedBatchNo);
                            }
                        });

                },
                error: function() {
                    alert("Error loading batches.");
                }
            });
        }



        function loadBatchData(batchId, rowIndex) { // Add rowIndex parameter
            console.log(rowIndex, "roIndex");
            $.ajax({
                url: '{{ url('/sellcorner/batche/data') }}/' + batchId,
                type: 'GET',
                success: function(response) {
                    console.log(response, "response");
                    $(`#unitsPerCarton${rowIndex}`).val(response?.batches?.no_of_units ||
                        ''); // Use rowIndex
                    $(`#availableQtyCarton${rowIndex}`).val(response?.batches?.quantity ||
                        ''); // Use rowIndex
                },
                error: function() {
                    alert("Error loading batch data.");
                }
            });
        }

        // customerList();
    });
</script>


<script>
    $(document).ready(function() {

        $(document).on("click", ".add-row", function() {
            const rowIndex = $(".skuRow").length;
            console.log(rowIndex, "anand roindex")
            // New row template with dynamic row index
            const newRow = `
        <div class="skuRow" id="skuRow_${rowIndex}">
            <div class="row">
                <!-- Customer and SKU Selection -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label for="selectSku_${rowIndex}" class="form-label">Select Product</label>
                        <select id="SKU_${rowIndex}" name="SKU" class="form-select select2 SKU sku-input">
                            <option selected disabled>Select Product</option>
                        </select>
                    </div>
                </div>

                <!-- Customer Type and Batch Selection -->
                <div class="col-md-6">

                    <div class="mb-3 batch-row">
                        <label for="batchNoSelect_${rowIndex}" class="form-label">Batch No</label>
                        <select id="batchNoSelect_${rowIndex}" class="form-select select2 batchNoSelect batch-input">
                            <option value="" disabled selected>Batch No</option>
                        </select>
                    </div>
                </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="saleDate_${rowIndex}" class="form-label">Sale Date</label>
                    <input type="date" class="form-control saleDate" id="saleDate_${rowIndex}" name="saleDate" 
                           max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                </div>
            </div>
                                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="unitsPerCarton_${rowIndex}">Number of Unit Per
                                            Carton</label>
                                        <input type="number" class="form-control unitsPerCarton" id="unitsPerCarton_${rowIndex}"
                                            name="unitsPerCarton" readonly placeholder="Enter units per carton" disabled>
                                    </div>
                                    <div class="mb-3">
                                      
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div  class="mb-3">
                                        <label class="form-label" for="availableQtyCarton_${rowIndex}">Available Total No of
                                            Carton</label>
                                        <input type="number" class="form-control availableQtyCarton" readonly id="availableQtyCarton_${rowIndex}"
                                            name="availableQtyCarton"
                                            placeholder="Enter available no of carton" disabled>
                                    </div>
                                </div>

                <!-- Packaging Type and Carton Selection -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Select Packaging Type</label>
                                    <div class="form-check">
                                            <input class="form-check-input packaging-type byCarton"  type="checkbox"
                                                id="byCarton_${rowIndex}" name="packagingType_${rowIndex}" value="byCarton">
                                            <label class="form-check-label" for="byCarton_${rowIndex}">
                                                By Carton
                                            </label>
                                            <div id="quantityBox_${rowIndex}"  style="display: none; width:20%; margin-top: 10px;">
                                                <label class="form-label" for="quantity_${rowIndex}">Enter Number of Carton</label>
                                                <input type="number" class="form-control" id="quantity_${rowIndex}" name="quantity_${rowIndex}" placeholder="Enter carton">
                                            </div>
                                        </div>
                    </div>
                </div>

                <!-- No of Cartons and Item Boxes -->
                <div class="col-md-6 mb-2">
                    <div class="row">
                        <div class="col-md-6 cartonInput" id="cartonInput_${rowIndex}" style="display: none;">
                            <div class="cartonCheckboxes" id="cartonCheckboxes_${rowIndex}" style="display: none;"></div>
                        </div>

                        <div class="col-md-6 itemBoxInput" id="itemBoxInput_${rowIndex}" style="display: none;">
                            <div id="itemBoxDropdown_${rowIndex}" class="itemBoxDropdown">
                                <label for="itemBoxId_${rowIndex}" class="form-label">Select Item Box ID</label>
                                <div class="itemBoxDropdown" id="itemBoxCheckboxes_${rowIndex}"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>`;

            const $newRow = $(newRow);
            $("#skuRows").append($newRow);

            // Select2 initialization
            const $skuDropdown = $newRow.find(`#SKU_${rowIndex}`);

            GetSKU($skuDropdown);
            $skuDropdown.select2({
                placeholder: "Select an option",
                allowClear: true
            });


            $newRow.find(`#batchNoSelect_${rowIndex}`).change(function() {
                const selectedBatch = $(this).val();

                if (selectedBatch) {
                    // loadCartons(selectedBatch, $newRow);
                    loadBatchData(selectedBatch, $newRow)
                }
            });

            $(document).on('change', `#byCarton_${rowIndex}`, function() {
                const quantityBox = $(`#quantityBox_${rowIndex}`);
                if ($(this).is(':checked')) {
                    quantityBox.show();
                } else {
                    quantityBox.hide();
                }
            });

            $(document).on("change", `#SKU_${rowIndex}`, function() {
                const selectedSku = $(this).val();
                var $row = $(this).closest('.row'); // Adjust to the correct parent container
                var $batchSelect = $row.find('.batchNoSelect');
                console.log($row, $batchSelect, "Debugging Row and Batch Select");

                if (selectedSku) {
                    loadBatchesAddMore(selectedSku,
                        $batchSelect); // Load batches for the selected SKU
                } else {
                    // $batchSelect.empty().append(
                    //     '<option value="" disabled selected>Batch No</option>'); // Clear options
                }
            });
        });

        function loadBatchData(batchId, $row) {
            console.log("yaap", batchId)
            $.ajax({
                url: '{{ url('/sellcorner/batche/data') }}/' + batchId,
                type: 'GET',
                success: function(response) {
                    console.log(response, "response")
                    const cartonCheckboxes = $row.find(".unitsPerCarton");
                    const itemBoxDropdown = $row.find(".availableQtyCarton");
                    cartonCheckboxes.val(response?.batches?.no_of_units)
                    itemBoxDropdown.val(response?.batches?.quantity)

                },
                error: function() {
                    alert("Error loading batches.");
                }
            });
        }


        function GetSKU($skuDropdown) {
            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {}, function(response) {
                console.log(response, $skuDropdown, "lll")

                $.each(response.products, function(index, product) {
                    $skuDropdown.append(
                        `<option value="${product?.product[0]?.id}">${product?.product[0]?.name}</option>`
                    );
                });

            });
        }

        function loadBatchesAddMore(sku, $batchSelect) {
            console.log(sku, $batchSelect, "abc")
            $.ajax({
                url: '{{ url('/sellcounter/batches') }}/' + sku,
                type: "GET",
                success: function(response) {
                    // $batchSelect.empty();

                    // $batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    if (response.batches && response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            $batchSelect.append(
                                `<option value="${batch.id}">${batch.batch_no} (${batch.valid_to})</option>`
                            );
                        });
                    } else {
                        // $batchSelect.append(
                        //     '<option value="" disabled>No batches available</option>');
                    }
                    $batchSelect.select2({
                        placeholder: "Select a Batch",
                        allowClear: true,
                    });
                },
                error: function(xhr, status, error) {
                    console.error("=== ERROR DETAILS ===");
                    console.error("HTTP Status Code:", xhr.status);
                    console.error("Status Text:", status);
                    console.error("Error Message:", error);
                    console.error("Full Response:", xhr.responseText);
                    console.error("Response Headers:", xhr.getAllResponseHeaders());

                    // Display user-friendly error
                    let errorMessage = "Error loading batches.\n\n";

                    if (xhr.status === 404) {
                        errorMessage += "Route not found. Check if the URL is correct.";
                    } else if (xhr.status === 500) {
                        errorMessage += "Server error. Check Laravel logs.";
                    } else if (xhr.status === 419) {
                        errorMessage += "CSRF token mismatch. Refresh the page.";
                    } else if (xhr.status === 0) {
                        errorMessage += "Network error. Check your connection.";
                    } else {
                        errorMessage += `Status: ${xhr.status}\n${xhr.responseText}`;
                    }

                    alert(errorMessage);
                }
            });
        }


        $(document).on("click", ".remove-row", function() {
            $(this).closest(".sku-row").remove();
        });
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        @if ((isset($showModal) && $showModal) || $errors->any() || session('success'))
            modal.style.display = "block";
        @endif
    });
</script>

<script>
    $("#productForm").on("submit", async function(e) { // Added async here
        e.preventDefault();
        const submitButton = $("#sellSubmit");
        let orderId = $('#orderId').val();

        // Array to store all rows' data
        const formData = [];

        // Get customer and customer type from the first row (common fields)
        const commonData = {
            customer: $("#customer").val(),
            customerType: $("#customerType").val(),
            customerTypeName: $("#customerTypeName").val(),
            paymentStatus: $("#paymentStatus").val()
        };

        // Loop through each SKU row
        $(".skuRow").each(function(index) {
            console.log(index, "skuIndex");
            const rowData = {
                ...commonData,
                rowIndex: index,
                sku: $(`#SKU_${index}`).val() || $("#SKU").val(),
                saleDate: $(`#saleDate_${index}`).val() || $("#saleDate").val(),
                batchNo: $(`#batchNoSelect_${index}`).val() || $("#batchNoSelect").val(),
                unitsPerCarton: $(`#unitsPerCarton_${index}`).val() || $("#unitsPerCarton")
                    .val(),
                availableQtyCarton: $(`#availableQtyCarton_${index}`).val() || $(
                    "#availableQtyCarton").val(),
                packagingType: {
                    byCarton: $(`#byCarton_${index}`).is(":checked") || $("#byCarton").is(
                        ":checked"),
                    quantity: $(`#quantity_${index}`).val() || $("#quantity").val()
                }
            };

            // Get selected carton checkboxes if they exist
            const cartonCheckboxes = [];
            $(`#cartonCheckboxes_${index} input:checked`).each(function() {
                cartonCheckboxes.push($(this).val());
            });
            if (cartonCheckboxes.length) {
                rowData.selectedCartons = cartonCheckboxes;
            }

            // Get selected item box checkboxes if they exist
            const itemBoxCheckboxes = [];
            $(`#itemBoxCheckboxes_${index} input:checked`).each(function() {
                itemBoxCheckboxes.push($(this).val());
            });
            if (itemBoxCheckboxes.length) {
                rowData.selectedItemBoxes = itemBoxCheckboxes;
            }

            formData.push(rowData);
        });

        // Log the collected data
        console.log("Form Data:", formData);

        // Disable submit button to prevent multiple submissions
        submitButton.prop('disabled', true); // Changed to jQuery's prop method
        const isUpdate = orderId && orderId !== '';
        const url = isUpdate ?
            `{{ route('sellCounter.update', '') }}/${orderId}` :
            "{{ route('sellCounter.store') }}";

        try {
            const response = await fetch(url, {
                method: isUpdate ? "PUT" : "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(formData),
            });

            if (response.ok) {
                const result = await response.json();
                toastr.success("Form submitted successfully!");
                console.log(result);
                window.location.href = "{{ route('sellCounter.index') }}";
            } else {
                const error = await response.json();
                toastr.error(
                    `Error: ${error.error}<br>File: ${error.file}<br>Line: ${error.line}`,
                    'Submission Error', {
                        closeButton: true,
                        timeOut: 5000,
                        extendedTimeOut: 2000,
                        progressBar: true
                    }
                );
            }
        } catch (err) {
            toastr.error(`An error occurred: ${err.message}`, 'Error', {
                closeButton: true,
                timeOut: 5000,
                extendedTimeOut: 2000,
                progressBar: true
            });
            console.error("Submission error:", err);
        } finally {
            // Re-enable the submit button
            submitButton.prop('disabled', false); // Changed to jQuery's prop method
        }
    });
</script>

</body>

</html>
