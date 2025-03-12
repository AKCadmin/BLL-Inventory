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
                    <div id="skuRows">
                        <div class="skuRow">
                            <div class="row">
                                <!-- Customer and Customer Type in one column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Sell Type</label>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="internalPurchase" name="internal_purchase">
                                            <label class="form-check-label" for="internalPurchase">Internal Sale</label>
                                        </div>
                                    </div>
                                </div>
                                @if(auth()->user()->role == 1)
                                <div class="col-md-6">
                                <label for="autoSizingSelect">Select Organization</label>
                                
                                    <select id="organizationName" name="organizationName" class="form-control custom-select">
                                        <option value="">Select Organization</option>
                                        @foreach ($organizations as $organization)
                                            <option value="{{ $organization->name }}">{{ str_replace('_', ' ', $organization->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <div class="col-md-6">
                                </div>
                                @endif

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
                                        <input type="text" disabled readonly class="form-control"
                                            id="customerTypeName">
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="unitsPerCarton">Number of Unit Per
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
                                        {{-- <label class="form-label">Select Packaging Type</label> --}}
                                        <div class="form-check">
                                            <input class="form-check-input packaging-type byCarton" type="checkbox"
                                                id="byCarton" name="packagingType" value="byCarton">
                                            <label class="form-check-label" for="byCarton">
                                                Sale
                                            </label>
                                            <div id="quantityBox" style="display: none; width:20%; margin-top: 10px;">
                                                <label class="form-label" for="quantity">Enter Number of Carton</label>
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
        $('#organization-filter').hide();

        GetSKU();
        customerList();

        function GetSKU() {

            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {},
                function(response) {
                    console.log(response.products, "gggg")
                    if (response.products) {
                        $('.SKU').html('<option selected disabled>Select Product</option>');
                        $.each(response.products, function(index, product) {

                            console.log(product?.product[0]?.id, "product")
                            // $('.SKU').append(
                            //     `<option value="${product.sku}">${product.sku}</option>`
                            // );
                            $('.SKU').append(
                                `<option value="${product?.product[0]?.id}">${product?.product[0].name}</option>`
                            );
                        });
                    }
                }
            );
        }

        $('#internalPurchase').change(function() {
            if ($(this).is(':checked')) {
                let url = `{{ route('retail.customers.list') }}`;
                ajaxRequest(url, 'GET', {},
                    function(response) {
                        if (response.customers && response.customers.length > 0) {
                            $('#customer').html(
                                '<option selected disabled>Select Customer</option>');
                            $.each(response.customers, function(index, customer) {

                                $('#customer').append(
                                    `<option value="${customer.id}">${customer?.name}</option>`
                                );
                            });
                        }
                    }
                );
            } else {
                $('#customerDiv').hide();
                customerList()
            }
        });

        $('input[name="packagingType"]').change(function() {
            if ($('#byCarton').is(':checked')) {
                $('#quantityBox').slideDown();
            } else {
                $('#quantityBox').slideUp();
            }
            console.log($(this).val(), "jj")
            if ($(this).val() == 'byCarton') {

                // $('.cartonInput').show();
                // $('.itemBoxInput').hide();
                // $('.cartonCheckboxes').find('input[type="checkbox"]').prop('checked', false);
                // $('.cartonCheckboxes').find('input[type="number"]').val('');
            } else if ($(this).val() == 'byItemBox') {
                // $('.itemBoxInput').show();
                // $('.cartonInput').hide();
                // $('.itemBoxCheckboxes').find('input[type="checkbox"]').prop('checked', false);
                // $('.itemBoxCheckboxes').find('input[type="number"]').val('');
            }
        });

        function customerList() {
            let url = `{{ route('customers.list') }}`;
            ajaxRequest(url, 'GET', {},
                function(response) {
                    console.log(response, "response")
                    if (response.customers && response.customers.length > 0) {
                        $('#customer').html('<option selected disabled>Select Product Customer</option>');
                        $.each(response.customers, function(index, customer) {

                            $('#customer').append(
                                `<option value="${customer.id}">${customer?.name}</option>`
                            );
                        });
                    }
                }
            );
        }

        $('#customer').on('change', function() {
            let customerId = $(this).val();
            GetCustomer(customerId);
        })

        function GetCustomer(customerId) {
            let url = `{{ route('customers.list') }}`;
            ajaxRequest(url, 'GET', {
                    customerId
                },
                function(response) {
                    console.log(response, "responsennnnn")
                    if (response.customers) {
                        $.each(response.customers, function(index, customer) {
                            console.log(customer, "customertyope")
                            $('#customerTypeName').val(
                                `${customer?.type_of_customer} (${customer?.credit_limit})`)
                            $('#customerType').val(customer.id)
                            // $('#customerType').append(
                            //     `<option selected readonly value="${customer.id}">${customer?.type_of_customer} (${customer?.credit_limit})</option>`
                            // );
                        });
                    }
                }
            );
        }

        $('.SKU').on('change', function() {
            var selectedSku = $(this).val();

            if (selectedSku) {
                loadBatches(selectedSku);
            } else {
                $('.batchNoSelect').empty().append(
                    '<option value="" disabled selected>Batch No</option>'); // Clear batch options
            }
        });

        function loadBatches(sku) {

            $.ajax({
                url: '{{ url('/sellcounter/batches') }}/' + sku,
                type: 'GET',
                success: function(response) {
                    var batchSelect = $('.batchNoSelect');
                    batchSelect.empty();

                    batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    if (response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            console.log(batch.batch_id,"jjjaaj")
                            batchSelect.append('<option value="' + batch.batch_id +
                                '">' + batch.batch_no + '</option>');
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

        $('.batchNoSelect').on('change', function() {
            var selectedBatch = $(this).val();
              console.log(selectedBatch,"selectedBatch")
            if (selectedBatch) {
                loadBatchData(selectedBatch);
            } else {

            }
        });

        function loadBatchData(batchId) {
            $.ajax({
                url: '{{ url('/sellcorner/batche/data') }}/' + batchId,
                type: 'GET',
                success: function(response) {
                    console.log(response, "response")
                    $('#unitsPerCarton').val(response?.batches?.no_of_units)
                    $('#availableQtyCarton').val(response?.batches?.quantity)

                },
                error: function() {
                    alert("Error loading batches.");
                }
            });
        }

        function loadCartons(batch) {
            $.ajax({
                url: '{{ url('/sellcounter/cartons') }}/' + batch,
                type: 'GET',
                success: function(response) {

                    var cartons = response.cartons;
                    var numCartons = cartons.length;

                    $('#noOfCartonsLebal').text('Provide No of Cartons (' + numCartons +
                        ' available)');
                    $('#noOfItemsLebal').text('Provide No of Item Boxes (' + numCartons +
                        ' available)');

                    $('.cartonCheckboxes').empty();

                    cartons.forEach(function(carton, index) {
                        if (carton.no_of_items_inside >
                            99) {
                            var checkboxHtml = `
                    <div class="form-check carton-row">
                        <input class="form-check-input carton-input" type="checkbox" id="carton${index}" value="${carton.carton_number}">
                        <label class="form-check-label" for="carton${index}">
                            ${carton.carton_number} (${carton.no_of_items_inside || 0} items available)
                        </label>
                    </div>
                    `;
                            $('#cartonCheckboxes').append(checkboxHtml);
                        } else {
                            $('.cartonCheckboxes').append(
                                '<p>No cartons available with more than 100 items.</p>');
                        }
                    });
                    // if ($('#cartonCheckboxes').children().length == 0) {
                    //     $('#cartonCheckboxes').append(
                    //         '<p>No cartons available with more than 100 items.</p>');
                    // }
                    $('.cartonCheckboxes').show();

                    var itemBoxes = response.cartons;
                    var numItemBoxes = itemBoxes.length;

                    $('#noOfItemsLebal').text('Provide No of Item Boxes (' + numItemBoxes +
                        ' available)');
                    $('.itemBoxCheckboxes').empty();

                    itemBoxes.forEach(function(itemBox, index) {
                        if (itemBox.no_of_items_inside >
                            0) { // Only show checkbox if items are available
                            var checkboxHtml = `
                    <div class="form-check carton-items-row">
                        <input class="form-check-input carton-items-input" type="checkbox" id="itemBox${index}" name="itemBox[]" value="${itemBox.carton_number}">
                        <label class="form-check-label" for="itemBox${index}">
                            ${itemBox.carton_number} (${itemBox.no_of_items_inside || 0} items available)
                        </label>
                        <input class="form-control mt-2 quantity-items-input" type="number" id="itemBoxQuantity${index}" name="itemBoxQuantity[]" min="1" max="${itemBox.no_of_items_inside || 0}" placeholder="Enter quantity" style="width: 120px; display: none;">
                    </div>
                    `;
                            $('.itemBoxCheckboxes').append(checkboxHtml);
                        }
                    });
                    $('.itemBoxDropdown').show();

                },
                error: function() {
                    alert("Error loading cartons.");
                }
            });
        }
        $(document).on('change', '.carton-items-input', function() {
            var checkbox = $(this);
            var quantityInput = checkbox.closest('.form-check').find('.quantity-items-input');

            if (checkbox.is(':checked')) {
                quantityInput.show();
            } else {
                quantityInput.hide();
            }
        })

    });
</script>


<script>
    $(document).ready(function() {

        $(document).on("click", ".add-row", function() {
            const rowIndex = $(".skuRow").length;

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
                                                Sale
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
        });

        function loadBatchData(batchId, $row) {
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

        // Function to load cartons dynamically based on batch
        function loadCartons(batch, $row) {
            $.ajax({
                url: '{{ url('/sellcounter/cartons') }}/' + batch,
                type: "GET",
                success: function(response) {
                    const cartons = response.cartons || [];
                    const $cartonCheckboxes = $row.find(".cartonCheckboxes");
                    const $itemBoxDropdown = $row.find(".itemBoxDropdown");

                    $cartonCheckboxes.empty();
                    $itemBoxDropdown.empty();

                    // Load cartons
                    if (cartons.length > 0) {
                        cartons.forEach(function(carton, index) {
                            const cartonHtml = `
                        <div class="form-check carton-row">
                            <input class="form-check-input carton-input" type="checkbox" id="carton_${index}" value="${carton.carton_number}">
                            <label class="form-check-label" for="carton_${index}">
                                ${carton.carton_number} (${carton.no_of_items_inside} items)
                            </label>
                        </div>`;
                            $cartonCheckboxes.append(cartonHtml);
                        });
                        $cartonCheckboxes.show();
                    } else {
                        $cartonCheckboxes.append('<p>No cartons available.</p>').show();
                    }

                    // Load item boxes
                    const itemBoxes = response.cartons;
                    if (itemBoxes.length > 0) {
                        itemBoxes.forEach(function(itemBox, index) {
                            if (itemBox.no_of_items_inside > 0) {
                                const itemBoxHtml = `
                            <div class="form-check carton-items-row">
                                <input class="form-check-input carton-items-input" type="checkbox" id="itemBox_${index}" name="itemBox[]" value="${itemBox.carton_number}">
                                <label class="form-check-label " for="itemBox_${index}">
                                    ${itemBox.carton_number} (${itemBox.no_of_items_inside} items)
                                </label>
                                <input class="form-control mt-2 quantity-items-input" type="number" id="itemBoxQuantity_${index}" name="itemBoxQuantity[]" min="1" max="${itemBox.no_of_items_inside}" placeholder="Enter quantity" style="width: 120px; display: none;">
                            </div>`;
                                $itemBoxDropdown.append(itemBoxHtml);
                            }
                        });
                        $itemBoxDropdown.show();
                    } else {
                        $itemBoxDropdown.append('<p>No item boxes available.</p>').show();
                    }
                },
                error: function() {
                    alert("Error loading cartons.");
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

        // Handle SKU change
        $(document).on("change", ".SKU", function() {
            const selectedSku = $(this).val();
            var $row = $(this).closest('.row'); // Adjust to the correct parent container
            var $batchSelect = $row.find('.batchNoSelect');
            console.log($row, $batchSelect, "Debugging Row and Batch Select");

            if (selectedSku) {
                loadBatches(selectedSku, $batchSelect); // Load batches for the selected SKU
            } else {
                $batchSelect.empty().append(
                    '<option value="" disabled selected>Batch No</option>'); // Clear options
            }
        });

        function loadBatches(sku, $batchSelect) {
            console.log(sku, $batchSelect, "abc")
            $.ajax({
                url: '{{ url('/sellcounter/batches') }}/' + sku,
                type: "GET",
                success: function(response) {
                    $batchSelect.empty();

                    $batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    if (response.batches && response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            console.log(batch,"abcbatch")
                            $batchSelect.append(
                                `<option value="${batch.batch_id}">${batch.batch_no} (${batch.valid_to})</option>`
                            );
                        });
                    } else {
                        $batchSelect.append(
                            '<option value="" disabled>No batches available</option>');
                    }
                    $batchSelect.select2({
                        placeholder: "Select a Batch",
                        allowClear: true,
                    });
                },
                error: function() {
                    alert("Error loading batches.");
                },
            });
        }

        $(document).on("change", ".batchNoSelect", function() {
            const $row = $(this).closest(".sku-row");
            const selectedBatch = $(this).val();

            if (selectedBatch) {
                loadCartons(selectedBatch, $row);
            }
        });


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

        // Array to store all rows' data
        const formData = [];

        // Get customer and customer type from the first row (common fields)
        const commonData = {
            customer: $("#customer").val(),
            customerType: $("#customerType").val(),
            customerTypeName: $("#customerTypeName").val()
        };

        // Loop through each SKU row
        $(".skuRow").each(function(index) {
            const rowData = {
                ...commonData,
                rowIndex: index,
                sku: $(`#SKU_${index}`).val() || $("#SKU").val(),
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

        try {
            const response = await fetch("{{ route('sellCounter.store') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(formData),
            });
            const result = await response.json();
            if (response.ok) {          
                toastr.success("Form submitted successfully!");
                console.log(result);
                window.location.href = "{{ route('sellCounter.index') }}";
            } else {
                // Handle validation errors (422 status)
                if (response.status === 422) {
                    const errorMessages = result.messages;
                    let errorHtml = '<ul style="list-style-type: none; padding-left: 0;">';

                    // Convert validation errors into readable format
                    Object.keys(errorMessages).forEach(key => {
                        errorMessages[key].forEach(message => {
                            // Extract the index from the key (e.g., "0.customer" -> "0")
                            const rowIndex = key.split('.')[0];
                            // Make the message more user-friendly
                            const friendlyMessage = message.replace(/\.\d+/g,
                                ` in row ${parseInt(rowIndex) + 1}`);
                            errorHtml += `<li>${friendlyMessage}</li>`;
                        });
                    });

                    errorHtml += '</ul>';

                    toastr.error(errorHtml, 'Validation Error', {
                        closeButton: true,
                        timeOut: 0, // Don't auto-close for validation errors
                        extendedTimeOut: 0,
                        progressBar: false,
                        enableHtml: true,
                        escapeHtml: false
                    });
                } else {
                    // Handle other types of errors
                    toastr.error(
                        `Error: ${result.error}<br>File: ${result.file}<br>Line: ${result.line}`,
                        'Submission Error', {
                            closeButton: true,
                            timeOut: 5000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            enableHtml: true
                        }
                    );
                }
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
