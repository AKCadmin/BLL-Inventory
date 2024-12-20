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
                                <input type="hidden" id="orderId" value="{{ $skuData['orderId'] }}">
                                <!-- Customer and Customer Type in one column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer" class="form-label">Select Customer</label>
                                        <input class="form-control" id="customer" type="input" id="">
                                    </div>


                                    {{-- <div class="mb-3">
                                        <label for="selectSku" class="form-label">Select SKU</label>
                                        <select id="SKU" name="SKU" class="form-select select2 SKU sku-input">
                                            <option selected disabled>Select Product / SKU</option>
                                        </select>
                                    </div> --}}
                                </div>

                                <!-- SKU and Batch in one column -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label for="customerType" class="form-label">Select Customer Type</label>
                                        <select class="form-select customer-type" id="customerType" required>
                                            <option value="" disabled selected>Select customer type</option>
                                            <option value="hospital">hospital</option>
                                            <option value="wholesale">wholesaler</option>
                                            <option value="retailer">retailer</option>
                                        </select>
                                    </div>

                                    {{-- <div class="mb-3 batch-row">
                                        <label for="batchNoSelect" class="form-label">Batch No</label>
                                        <select id="batchNoSelect"
                                            class="form-select select2 batchNoSelect batch-input">
                                            <option value="" disabled selected>Batch No</option>
                                        </select>
                                    </div> --}}
                                </div>

                                {{-- <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Select Packaging Type</label>
                                        <div class="form-check">
                                            <input class="form-check-input packaging-type byCarton" type="radio"
                                                id="byCarton" name="packagingType" value="byCarton">
                                            <label class="form-check-label" for="byCarton">
                                                By Carton
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input packaging-type pakagingType byItemBox"
                                                type="radio" id="byItemBox" name="packagingType" value="byItemBox">
                                            <label class="form-check-label" for="byItemBox">
                                                By Item Box
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- No of Cartons and No of Item Boxes -->
                                <div class="col-md-6 mb-2">
                                    <div class="row">

                                        <div class="col-md-6 cartonInput" style="display: none;">

                                            <div class="cartonCheckboxes" id="cartonCheckboxes" style="display: none;">
                                                <label class="form-label">Select Carton IDs</label>

                                            </div>
                                        </div>

                                        <div class="col-md-6 itemBoxInput" id="" style="display: none;">

                                            <div id="" class="itemBoxDropdown" style="display: none;">
                                                <label for="itemBoxId" class="form-label">Select Item Box ID</label>
                                                <div class="itemBoxCheckboxes" id=""></div>
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

        $(document).on("click", ".add-row", function() {
            const rowIndex = $(".skuRow").length;

            // New row template with dynamic row index
            const newRow = `
        <div class="skuRow" id="skuRow_${rowIndex}">
            <div class="row">
                <!-- Customer and SKU Selection -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label for="selectSku_${rowIndex}" class="form-label">Select SKU</label>
                        <select id="SKU_${rowIndex}" name="SKU" class="form-select select2 SKU sku-input">
                            <option selected disabled>Select Product / SKU</option>
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

                <!-- Packaging Type and Carton Selection -->
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Select Packaging Type</label>
                        <div class="form-check">
                            <input class="form-check-input packaging-type" type="radio" id="byCarton_${rowIndex}"
                                name="packagingType_${rowIndex}" value="byCarton">
                            <label class="form-check-label" for="byCarton_${rowIndex}">
                                By Carton
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input packaging-type" type="radio" id="byItemBox_${rowIndex}"
                                name="packagingType_${rowIndex}" value="byItemBox">
                            <label class="form-check-label" for="byItemBox_${rowIndex}">
                                By Item Box
                            </label>
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
                            <div id="itemBoxDropdown_${rowIndex}" class="itemBoxDropdown" style="display: none;">
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
                    loadCartons(selectedBatch, $newRow);
                }
            });


            $newRow.find('.packaging-type').change(function() {
                if ($(this).val() === 'byCarton') {
                    $newRow.find(`#cartonInput_${rowIndex}`).show();
                    $newRow.find(`#itemBoxInput_${rowIndex}`).hide();
                } else if ($(this).val() === 'byItemBox') {
                    $newRow.find(`#itemBoxInput_${rowIndex}`).show();
                    $newRow.find(`#cartonInput_${rowIndex}`).hide();
                }
            });

            $(document).on('change', `.carton-items-input`, function() {
                var checkbox = $(this);
                var quantityInput = checkbox.closest('.form-check').find(
                    '.quantity-items-input');

                if (checkbox.is(':checked')) {
                    quantityInput.show();
                } else {
                    quantityInput.hide();
                }
            });

            $(document).on("change", ".SKU", function() {
                const selectedSku = $(this).val();
                var $row = $(this).closest('.row'); // Adjust to the correct parent container
                var $batchSelect = $row.find('.batchNoSelect');


                if (selectedSku) {
                    loadBatchesAdd(selectedSku,
                        $batchSelect); // Load batches for the selected SKU
                } else {
                    $batchSelect.empty().append(
                        '<option value="" disabled selected>Batch No</option>'
                    ); // Clear options
                }
            });
        });

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
                    // alert("Error loading cartons.");
                }
            });
        }


        function GetSKU($skuDropdown) {
            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {}, function(response) {
                if (response.products && response.products.length > 0) {
                    $skuDropdown.html(
                        '<option selected disabled>Select Product / SKU</option>'
                    );
                    $.each(response.products, function(index, product) {
                        $skuDropdown.append(
                            `<option value="${product.sku}">${product.sku}</option>`
                        );
                    });
                }
            });
        }

        // Handle SKU change
        // $(document).on("change", ".SKU", function() {
        //     const selectedSku = $(this).val();
        //     var $row = $(this).closest('.row'); // Adjust to the correct parent container
        //     var $batchSelect = $row.find('.batchNoSelect');


        //     if (selectedSku) {
        //         //loadBatchesAdd(selectedSku, $batchSelect); // Load batches for the selected SKU
        //     } else {
        //         $batchSelect.empty().append(
        //             '<option value="" disabled selected>Batch No</option>'); // Clear options
        //     }
        // });

        function loadBatchesAdd(sku, $batchSelect) {

            $.ajax({
                url: '{{ url('/sellcounter/batches') }}/' + sku,
                type: "GET",
                success: function(response) {
                    // $batchSelect.empty();

                    $batchSelect.append('<option value="" disabled selected>Batch No</option>');

                    if (response.batches && response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            console.log(batch.batch_no, "batchno")
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

        // function loadCartons(batch, $row) {
        //     $.ajax({
        //         url: '{{ url('/sellcounter/cartons') }}/' + batch,
        //         type: "GET",
        //         success: function(response) {
        //             const cartons = response.cartons || [];
        //             const $cartonCheckboxes = $row.find(".cartonCheckboxes");
        //             const $itemBoxDropdown = $row.find(".item-box-dropdown");

        //             // Empty the previous content in the row
        //             $cartonCheckboxes.empty();
        //             $itemBoxDropdown.empty();

        //             // Load cartons
        //             if (cartons.length > 0) {
        //                 cartons.forEach(function(carton, index) {
        //                     const cartonHtml = `
        //                 <div class="form-check">
        //                     <input class="form-check-input" type="checkbox" id="carton${index}" value="${carton.carton_number}">
        //                     <label class="form-check-label" for="carton${index}">
        //                         ${carton.carton_number} (${carton.no_of_items_inside} items)
        //                     </label>
        //                 </div>`;
        //                     $cartonCheckboxes.append(cartonHtml);
        //                     console.log(cartonHtml,"cartonHtml")
        //                 });
        //                 $cartonCheckboxes.show();
        //             } else {
        //                 $cartonCheckboxes.append('<p>No cartons available.</p>').show();
        //             }

        //             // Load item boxes
        //             const itemBoxes = response.cartons;
        //             if (itemBoxes.length > 0) {
        //                 itemBoxes.forEach(function(itemBox, index) {
        //                     if (itemBox.no_of_items_inside >
        //                         0) { // Only show if items are available
        //                         const itemBoxHtml = `
        //                     <div class="form-check carton-items-row">
        //                         <input class="form-check-input carton-items-input" type="checkbox" id="itemBox${index}" name="itemBox[]" value="${itemBox.carton_number}">
        //                         <label class="form-check-label" for="itemBox${index}">
        //                             ${itemBox.carton_number} (${itemBox.no_of_items_inside} items)
        //                         </label>
        //                         <input class="form-control mt-2 quantity-items-input" type="number" id="itemBoxQuantity${index}" name="itemBoxQuantity[]" min="1" max="${itemBox.no_of_items_inside}" placeholder="Enter quantity" style="width: 120px;">
        //                     </div>`;
        //                         $itemBoxDropdown.append(itemBoxHtml);
        //                     }
        //                 });
        //                 $itemBoxDropdown.show();
        //             } else {
        //                 $itemBoxDropdown.append('<p>No item boxes available.</p>').show();
        //             }
        //         },
        //         error: function() {
        //             alert("Error loading cartons.");
        //         }
        //     });
        // }



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
    $(document).ready(function() {
        // GetSKU();

        $('input[name="packagingType"]').change(function() {

            if ($(this).val() == 'byCarton') {
                $('.cartonInput').show();
                $('.itemBoxInput').hide();
                $('.cartonCheckboxes').find('input[type="checkbox"]').prop('checked', false);
                $('.cartonCheckboxes').find('input[type="number"]').val('');
            } else if ($(this).val() == 'byItemBox') {
                $('.itemBoxInput').show();
                $('.cartonInput').hide();
                $('.itemBoxCheckboxes').find('input[type="checkbox"]').prop('checked', false);
                $('.itemBoxCheckboxes').find('input[type="number"]').val('');
            }
        });


        function GetSKU() {
            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {},
                function(response) {
                    if (response.products && response.products.length > 0) {
                        $('.SKU').html('<option selected disabled>Select Product / SKU</option>');
                        $.each(response.products, function(index, product) {
                            $('.SKU').append(
                                `<option value="${product.sku}">${product.sku}</option>`
                            );
                        });
                    }
                }
            );
        }

        $('.SKU').on('change', function() {
            var selectedSku = $(this).val();

            if (selectedSku) {
                // loadBatches(selectedSku);
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

                            batchSelect.append('<option value="' + batch.batch_no +
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

            if (selectedBatch) {
                loadCartons(selectedBatch);
            } else {

            }
        });

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


        // const skuData = {
        //     "orderId" : "798530",
        //     "customer": {
        //         "name": "ajay",
        //         "type": "wholesale"
        //     },
        //     "items": [{
        //             "sku": "K77672",
        //             "batch": "G123",
        //             "batches": [{
        //                 "batch_no": "G123",
        //                 "packaging_type": "By Carton",
        //                 "quantity": 100
        //             }]
        //         },
        //         {
        //             "sku": "K876781",
        //             "batch": "B115",
        //             "batches": [{
        //                 "batch_no": "B115-1",
        //                 "packaging_type": "By Item Box",
        //                 "quantity": 35
        //             }]
        //         },
        //         {
        //             "sku": "k876762",
        //             "batch": "B102",
        //             "batches": [{
        //                     "batch_no": "B102-1",
        //                     "packaging_type": "By Item Box",
        //                     "quantity": 23
        //                 },
        //                 {
        //                     "batch_no": "B102-2",
        //                     "packaging_type": "By Item Box",
        //                     "quantity": 2
        //                 }
        //             ]
        //         }
        //     ]
        // };

        const skuData = @json($skuData);

        skuData.items.forEach(item => {
            const rowIndex = $(".skuRow").length;

            // New row template with dynamic row index
            const newRow = `
<div class="skuRow" id="skuRow_${rowIndex}">
    <div class="row">
        <!-- Customer and SKU Selection -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="selectSku_${rowIndex}" class="form-label">Select SKU</label>
                <select id="SKU_${rowIndex}" name="SKU" class="form-select select2 SKU sku-input">
                    <option selected disabled>Select Product / SKU</option>
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

        <!-- Packaging Type and Carton Selection -->
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Select Packaging Type</label>
                <div class="form-check">
                    <input class="form-check-input packaging-type" type="radio" id="byCarton_${rowIndex}"
                        name="packagingType_${rowIndex}" value="byCarton">
                    <label class="form-check-label" for="byCarton_${rowIndex}">
                        By Carton
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input packaging-type" type="radio" id="byItemBox_${rowIndex}"
                        name="packagingType_${rowIndex}" value="byItemBox">
                    <label class="form-check-label" for="byItemBox_${rowIndex}">
                        By Item Box
                    </label>
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
                    <div id="itemBoxDropdown_${rowIndex}" class="itemBoxDropdown" style="display: none;">
                        <label for="itemBoxId_${rowIndex}" class="form-label">Select Item Box ID</label>
                        <div class="itemBoxDropdown" id="itemBoxCheckboxes_${rowIndex}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;

            // Append the new row to the container
            // Append the new row to the container
            const $newRow = $(newRow);
            $("#skuRows").append($newRow);
            let count = rowIndex - 1;
            $('#customer').val(skuData.customer.name);
            $('#customerType').val(skuData.customer.type);

            GetSKU(rowIndex, skuData.items[count].sku);
            // Populate SKU dropdown for the new row
            skuData.items.forEach(skuItem => {

                $(`#SKU_${rowIndex}`).append(
                    `<option value="${skuItem.sku}">${skuItem.sku}</option>`);
            });

            // Pre-select the current SKU
            $(`#SKU_${rowIndex}`).val(item.sku).trigger('change');

            // Listen for changes in the item box checkbox
            $(document).on('change', `.carton-items-input`, function() {
                var checkbox = $(this);
                var quantityInput = checkbox.closest('.form-check').find(
                    '.quantity-items-input');

                // Toggle the visibility of the quantity input based on the checkbox state
                if (checkbox.is(':checked')) {
                    quantityInput.show();
                } else {
                    quantityInput.hide();
                }
            });

            // This function should run after packaging type is selected
            item.batches.forEach(batch => {
                const $row = $(`#row_${rowIndex}`); // Find the row container dynamically

                if (batch.packaging_type === "By Carton") {
                    // Select By Carton
                    $(`#byCarton_${rowIndex}`).prop("checked", true);
                    $(`#byItemBox_${rowIndex}`).prop("checked", false);

                    // Hide all item box quantity inputs when switching to By Carton
                    $(`#itemBoxCheckboxes_${rowIndex}`).find('.quantity-items-input').hide();

                    // Show the carton input and hide the item box input
                    $(`#cartonInput_${rowIndex}`).show();
                    $(`#itemBoxInput_${rowIndex}`).hide();

                } else if (batch.packaging_type === "By Item Box") {
                    // Select By Item Box
                    $(`#byItemBox_${rowIndex}`).prop("checked", true);
                    $(`#byCarton_${rowIndex}`).prop("checked", false);

                    $(`.itemBoxDropdown`).find('.quantity-items-input').show();

                    // Show the item box input and hide the carton input
                    $(`#itemBoxInput_${rowIndex}`).show();
                    $(`#cartonInput_${rowIndex}`).hide();
                }

            });
            count++
            $(`#SKU_${rowIndex}`).on("change", function() {
                const selectedSku = $(this).val();
                const providedBatchNo = item.batch;
                const $batchSelect = $(`#batchNoSelect_${rowIndex}`);
                loadBatches(selectedSku, $batchSelect, providedBatchNo);

            });

            // Trigger the change event to load batches for the pre-selected SKU
            $(`#SKU_${rowIndex}`).trigger('change');

        });

        function GetSKU(rowIndex, selectedSku) {
            let url = `{{ route('sell.product.getData') }}`;
            ajaxRequest(url, 'GET', {}, function(response) {
                if (response.products && response.products.length > 0) {
                    const $skuSelect = $(`#SKU_${rowIndex}`);
                    console.log($skuSelect, "skuSelect");

                    // Clear existing options and add a default option
                    $skuSelect.html(
                        '<option selected disabled>Select Product / SKU</option>');

                    // Use a set to ensure unique SKUs
                    const uniqueSkus = new Set();

                    // Append SKU options ensuring uniqueness
                    $.each(response.products, function(index, product) {
                        if (!uniqueSkus.has(product.sku)) {
                            uniqueSkus.add(product.sku);
                            $skuSelect.append(
                                `<option value="${product.sku}">${product.sku}</option>`
                            );
                        }
                    });

                    console.log(selectedSku, "selectedSku");

                    // Delay to ensure options are populated before setting selected value
                    setTimeout(function() {
                        if (selectedSku) {
                            $skuSelect.val(selectedSku);
                            $skuSelect.trigger(
                                'change'
                            ); // Trigger change for select2 reinitialization

                            console.log("Selected SKU set:", selectedSku);

                            // Reinitialize select2 to apply the selected value
                            if ($skuSelect.hasClass('select2')) {
                                $skuSelect.trigger('change');
                            }
                        }
                    }, 200); // Increase delay to 200ms
                }
            });
        }

        // Function to load batches dynamically via AJAX
        function loadBatches(sku, $batchSelect, providedBatchNo) {
            $.ajax({
                url: `/sellcounter/batches/${sku}`, // Update this URL as per your routing
                type: "GET",
                success: function(response) {
                    const $row = $batchSelect.closest(".skuRow");
                    $batchSelect.empty();
                    $batchSelect.append(
                        '<option value="" disabled selected>Batch No</option>');

                    if (response.batches && response.batches.length > 0) {
                        response.batches.forEach(function(batch) {
                            $batchSelect.append(
                                `<option value="${batch.batch_no}">${batch.batch_no}</option>`
                            );
                        });
                    } else {
                        $batchSelect.append(
                            '<option value="" disabled>No batches available</option>'
                        );
                    }

                    // Check if provided batch_no exists and select it, else leave it empty
                    if (providedBatchNo && response.batches.some(batch => batch
                            .batch_no ===
                            providedBatchNo)) {
                        $batchSelect.val(providedBatchNo).trigger('change');

                    } else {
                        // If no provided batch_no or batch_no not found, don't select any batch
                        $batchSelect.val('').trigger('change');

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

        function loadCartons(batch, $row, skuData) {
            const rowIndex = $row.attr("id").split("_")[1];
            console.log(rowIndex, "rowIndexrowIndex");

            // Find the current item's batch data
            const currentItem = skuData.items.find(item => item.batch === batch);

            if (!currentItem) {
                console.error("Batch not found in skuData");
                return;
            }

            const batchNumbers = currentItem.batches.map(b => b.batch_no); // Extract batch_no array
            const batchQuantities = Object.fromEntries(
                currentItem.batches.map(b => [b.batch_no, b
                    .quantity
                ]) // Create a map of batch_no to quantity
            );

            $.ajax({
                url: `/sellcounter/cartons/${batch}`, // Update this URL as needed
                type: "GET",
                success: function(response) {
                    const cartons = response.cartons || [];
                    console.log(cartons, "cartons")
                    const $cartonCheckboxes = $row.find(`.cartonCheckboxes`); // Target specific row
                    const $itemBoxDropdown = $row.find(`.itemBoxDropdown`); // Target specific row

                    $cartonCheckboxes.empty().hide();
                    $itemBoxDropdown.empty().hide();

                    // Load carton checkboxes
                    if (cartons.length > 0) {
                        cartons.forEach(function(carton, index) {
                            const isChecked = batchNumbers.includes(carton
                                .carton_number);
                            console.log(isChecked, "isChecked")
                            if (carton.no_of_items_inside > 99) {
                                const cartonHtml = `
                        <div class="form-check carton-row">
                            <input class="form-check-input carton-input" type="checkbox" id="carton_${rowIndex}_${index}" value="${carton.carton_number}" ${isChecked ? "checked" : ""}>
                            <label class="form-check-label" for="carton_${rowIndex}_${index}">
                                ${carton.carton_number} (${carton.no_of_items_inside} items)
                            </label>
                        </div>`;
                                $cartonCheckboxes.append(cartonHtml);
                            }
                        });
                        $cartonCheckboxes.show();
                    } else {
                        $cartonCheckboxes.append('<p>No cartons available.</p>').show();
                    }

                    // Load item boxes
                    if (cartons.length > 0) {
                        cartons.forEach(function(itemBox, index) {
                            const isChecked = batchNumbers.includes(itemBox
                                .carton_number); // Compare with item box batch_no
                            const quantity = batchQuantities[itemBox.carton_number] || "";
                            const showQuantityInput = isChecked && quantity > 0;

                            if (itemBox.no_of_items_inside > 0) {
                                const itemBoxHtml = `
                        <div class="form-check carton-items-row">
                            <input class="form-check-input carton-items-input" type="checkbox" id="itemBox_${rowIndex}_${index}" name="itemBox[]" value="${itemBox.carton_number}" ${isChecked ? "checked" : ""}>
                            <label class="form-check-label" for="itemBox_${rowIndex}_${index}">
                                ${itemBox.carton_number} (${itemBox.no_of_items_inside} items)
                            </label>
                            <input class="form-control mt-2 quantity-items-input" type="number" id="itemBoxQuantity_${rowIndex}_${index}" name="itemBoxQuantity[]" min="1" max="${itemBox.no_of_items_inside}" value="${quantity}" placeholder="Enter quantity" style="width: 120px; ${showQuantityInput ? 'display: block;' : 'display: none;'}">
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
                    console.error("Error loading cartons.");
                }
            });
        }



        $(document).on("change", ".batchNoSelect", function() {
            const $batchSelect = $(this);
            const selectedBatch = $batchSelect.val();
            const $row = $batchSelect.closest(".skuRow");

            if (selectedBatch) {
                loadCartons(selectedBatch, $row, skuData); // Pass skuData here
            } else {
                console.warn("Please select a valid batch.");
            }
        });





        function loadPackagingTypes(batches) {
            batches.forEach((batch, index) => {
                const rowIndex = $(".skuRow").length - 1; // Get the last row index

                if (batch.packaging_type === "By Carton") {
                    $(`#byCarton_${rowIndex}`).prop('disabled', false);
                    $(`#byItemBox_${rowIndex}`).prop('disabled', true);
                } else if (batch.packaging_type === "By Item Box") {
                    $(`#byItemBox_${rowIndex}`).prop('disabled', false);
                    $(`#byCarton_${rowIndex}`).prop('disabled', true);
                }
            });
        }

        $(document).on('change', 'input[name^="packagingType_"]', function() {
            const rowIndex = $(this).attr('name').split('_')[1];
            console.log(rowIndex, "rowIndexmmmm")
            if ($(this).val() == 'byCarton') {
                console.log("by cartoon")
                $(`#cartonInput_${rowIndex}`).show();
                $(`#itemBoxInput_${rowIndex}`).hide();

                // Reset the values for the carton checkboxes and number inputs
                $(`#cartonCheckboxes_${rowIndex}`).find('input[type="checkbox"]').prop(
                    'checked',
                    false);
                $(`#cartonCheckboxes_${rowIndex}`).find('input[type="number"]').val('');
            } else if ($(this).val() == 'byItemBox') {
                console.log("by byItemBox")
                // Show item box input and hide carton input for the specific row
                $(`#itemBoxInput_${rowIndex}`).show();
                $(`#cartonInput_${rowIndex}`).hide();

                // Reset the values for the item box checkboxes and number inputs
                $(`#itemBoxCheckboxes_${rowIndex}`).find('input[type="checkbox"]').prop(
                    'checked',
                    false);
                $(`#itemBoxCheckboxes_${rowIndex}`).find('input[type="number"]').val('');
            }
        });

        // $(document).on('change', `.carton-items-input`, function() {
        //         var checkbox = $(this);
        //         var quantityInput = checkbox.closest('.form-check').find(
        //             '.quantity-items-input');

        //         if (checkbox.is(':checked')) {
        //             quantityInput.show();
        //         } else {
        //             quantityInput.hide();
        //         }
        //     });

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productForm = document.getElementById("productForm");
        const submitButton = document.getElementById("sellSubmit");

        productForm.addEventListener("submit", async function(event) {
            event.preventDefault();

            // Collect data
            const skuDataForRow = [];
            const batchData = {};
            const skuRows = document.querySelectorAll(".skuRow");
            console.log(skuRows, "skuRows")
            skuRows.forEach((row, index) => {
                if (index === 0) return;
                const skuInput = row.querySelector(".sku-input");
                // console.log(`Row ${index}:`, skuInput);

                const customerTypeSelect = document.querySelector("#customerType");
                const packagingTypeRadio = row.querySelector(".packaging-type:checked");
                const batchInputField = row.querySelector(".batch-input");
                console.log(customerTypeSelect, packagingTypeRadio, batchInputField,
                    "packagingTypebbb")
                if (skuInput && customerTypeSelect && packagingTypeRadio &&
                    batchInputField) {
                    const skuValue = skuInput.value.trim();
                    const customerType = customerTypeSelect.value.trim();
                    const packagingType = packagingTypeRadio.value.trim();
                    const batchInput = batchInputField.value.trim();

                    if (!batchInput) {
                        alert("Batch input cannot be empty.");
                        return;
                    }

                    // Add data to skuDataForRow
                    skuDataForRow.push({
                        SKU: skuValue,
                        customerType: customerType,
                        packagingType: packagingType,
                    });

                    // Initialize batch data if it doesn't exist
                    if (!batchData[batchInput]) {
                        batchData[batchInput] = {
                            sku: [],
                            cartons: [],
                            numCartons: 0,
                            cartonData: [],
                            cartonItemsData: [],
                            packagingTypes: [],
                        };
                    }
                    console.log(batchData[batchInput], "batchDataBatchinput")
                    // Add SKU data to batchData
                    batchData[batchInput].sku.push({
                        SKU: skuValue,
                        customerType: customerType,
                        packagingType: packagingType,
                    });

                    // Collect packaging types
                    const packagingTypeInputs = row.querySelectorAll(".packaging-type");
                    packagingTypeInputs.forEach(packagingInput => {
                        if (packagingInput.checked) {
                            batchData[batchInput].packagingTypes.push(packagingInput
                                .value.trim());
                        }
                    });

                    // Collect carton data
                    // const cartonInputs = row.querySelectorAll(".carton-input");

                    // cartonInputs.forEach(cartonInput => {

                    //     if (cartonInput.checked) {
                    //         batchData[batchInput].cartons.push(cartonInput.value
                    //             .trim());
                    //         batchData[batchInput].cartonData.push(cartonInput.value
                    //             .trim());
                    //         batchData[batchInput].numCartons++;
                    //     }
                    // });
                    const cartonInputs = row.querySelectorAll(".carton-input");
                    cartonInputs.forEach(cartonInput => {
                        if (cartonInput.checked) {
                            // Check if packaging type is "byCarton"
                            if (batchData[batchInput].packagingTypes.includes(
                                    "byCarton")) {
                                if (!batchData[batchInput].cartons.includes(
                                        cartonInput.value.trim())) {
                                    batchData[batchInput].cartons.push(cartonInput
                                        .value.trim());
                                }
                                if (!batchData[batchInput].cartonData.includes(
                                        cartonInput.value.trim())) {
                                    batchData[batchInput].cartonData.push(
                                        cartonInput.value.trim());
                                }
                                batchData[batchInput].numCartons++;
                            }
                        }
                    });


                    // Collect carton items data
                    // const cartonItemsRows = row.querySelectorAll(".carton-items-row");
                    // cartonItemsRows.forEach(cartonItemRow => {
                    //     const cartonItemInput = cartonItemRow.querySelector(
                    //         ".carton-items-input");
                    //     const quantityItemInput = cartonItemRow.querySelector(
                    //         ".quantity-items-input");

                    //     if (cartonItemInput && quantityItemInput &&
                    //         quantityItemInput.value.trim() !== "") {
                    //         batchData[batchInput].cartonItemsData.push({
                    //             cartonItem: cartonItemInput.value.trim(),
                    //             quantityItem: quantityItemInput.value
                    //             .trim(),
                    //         });
                    //     }
                    // });

                    const cartonItemsRows = row.querySelectorAll(".carton-items-row");
                    cartonItemsRows.forEach(cartonItemRow => {
                        const cartonItemInput = cartonItemRow.querySelector(
                            ".carton-items-input");
                        const quantityItemInput = cartonItemRow.querySelector(
                            ".quantity-items-input");

                        if (
                            cartonItemInput &&
                            quantityItemInput &&
                            quantityItemInput.value.trim() !== ""
                        ) {
                            // Check if packaging type is "byItemBox"
                            if (batchData[batchInput].packagingTypes.includes(
                                    "byItemBox")) {
                                batchData[batchInput].cartonItemsData.push({
                                    cartonItem: cartonItemInput.value
                                    .trim(),
                                    quantityItem: quantityItemInput.value
                                        .trim(),
                                });
                            }
                        }
                    });

                }
            });
         
            const formObject = {
                orderId: document.querySelector("#orderId").value,
                customer: document.querySelector("#customer").value.trim(),
                skuData: skuDataForRow,
                batchData: batchData,
            };

            // Validate required fields
            if (!formObject.customer || skuDataForRow.length === 0 || Object.keys(batchData)
                .length === 0) {
                alert("Please fill in all required fields.");
                return;
            }

           
            submitButton.disabled = true;
            const orderId = document.querySelector("#orderId").value; 
            const updateUrl = `{{ route('sellCounter.update', ['sellCounter' => '__orderId__']) }}`.replace('__orderId__', orderId);  
            try {
                const response = await fetch(updateUrl, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: JSON.stringify(formObject),
                });

                if (response.ok) {
                    const result = await response.json();
                    toastr.success("Form submitted successfully!");
                    location.reload();
                } else {
                    const error = await response.json();
                    toastr.error(
                        `Error: ${error.error}<br>File: ${error.file}<br>Line: ${error.line}`);
                }
            } catch (err) {
                toastr.error("An unexpected error occurred.");
                console.error(err); // Log the error for debugging
            } finally {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });
</script>



{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const productForm = document.getElementById("productForm");
        const submitButton = document.getElementById("sellSubmit");

        productForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const skuData = {
                customer: {
                    name: document.querySelector("#customer").value,
                    type: document.querySelector("#customerType").value
                },
                items: []
            };

            const skuRows = document.querySelectorAll("#skuRows");

            skuRows.forEach(row => {
                const skuInput = row.querySelector(".sku-input");
                const batchInput = row.querySelector(".batch-input");
                const packagingTypeRadio = row.querySelector('.packaging-type:checked');
                const quantityInput = row.querySelector(".quantity-input");
                console.log(skuInput,batchInput,packagingTypeRadio,quantityInput,"quantityInput")
                if (skuInput && batchInput && packagingTypeRadio && quantityInput) {
                    const skuValue = skuInput.value;
                    const batchNo = batchInput.value;
                    const packagingType = packagingTypeRadio.value;
                    const quantity = parseInt(quantityInput.value, 10);
                    console.log(skuValue,batchNo,packagingType,quantity,"quantityInput")
                    // Check if the item with the same SKU exists
                    let existingItem = skuData.items.find(item => item.sku === skuValue);

                    if (!existingItem) {
                        // Create a new item entry
                        existingItem = {
                            sku: skuValue,
                            batch: batchNo,
                            batches: []
                        };
                        skuData.items.push(existingItem);
                    }
                    console.log(existingItem,"existingItem");
                    // Add batch details to the existing item
                    existingItem.batches.push({
                        batch_no: batchNo,
                        packaging_type: packagingType,
                        quantity: quantity
                    });
                }
            });
            console.log(skuData);
            // Check for validation
            if (!skuData.customer.name || !skuData.customer.type || skuData.items.length === 0) {
                alert("Please fill in all required fields.");
                return;
            }

            console.log(skuData); // Debugging to check data format

            // Disable submit button to prevent multiple submissions
            submitButton.disabled = true;

            try {
                const response = await fetch("{{ route('sellCounter.store') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(skuData),
                });

                if (response.ok) {
                    const result = await response.json();
                    toastr.success("Form submitted successfully!");

                    location.reload();
                } else {
                    const error = await response.json();
                    toastr.error(
                        `Error: ${error.error}<br>File: ${error.file}<br>Line: ${error.line}`
                    );
                }
            } catch (err) {
                toastr.error("An error occurred.", err.message);
            } finally {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });
</script> --}}

{{-- <script>
    $(document).ready(function() {
        // Example data
        const formData = {
            customer: "John Doe",
            customerType: "wholesale",
            selectedSku: 2,
            skuOptions: [{
                    id: 1,
                    name: "k876762"
                },
                {
                    id: 2,
                    name: "k876762"
                }
            ],
            selectedBatch: 101,
            batchOptions: [{
                    id: 101,
                    name: "B101"
                },
                {
                    id: 102,
                    name: "B102"
                }
            ],
            packagingType: "byCarton"
        };

        // Populate Customer
        $("#customer").val(formData.customer);

        // Populate Customer Type
        $("#customerType").val(formData.customerType);

        // Populate SKU Options
        formData.skuOptions.forEach(option => {
            const isSelected = option.id === formData.selectedSku ? "selected" : "";
            $("#SKU").append(`<option value="${option.id}" ${isSelected}>${option.name}</option>`);
        });

        // Populate Batch Options
        formData.batchOptions.forEach(option => {
            const isSelected = option.id === formData.selectedBatch ? "selected" : "";
            $("#batchNoSelect").append(
                `<option value="${option.id}" ${isSelected}>${option.name}</option>`);
        });

        // Set Packaging Type
        $(`input[name="packagingType"][value="${formData.packagingType}"]`).prop("checked", true);
    });
</script> --}}




</body>

</html>
