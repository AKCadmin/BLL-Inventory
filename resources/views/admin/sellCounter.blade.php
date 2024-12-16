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
                                        <label for="customer" class="form-label">Select Customer</label>
                                        <input class="form-control" id="customer" type="input" id="">
                                    </div>


                                    <div class="mb-3">
                                        <label for="selectSku" class="form-label">Select SKU</label>
                                        <select id="SKU" name="SKU" class="form-select select2 SKU sku-input">
                                            <option selected disabled>Select Product / SKU</option>
                                        </select>
                                    </div>
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

                                    <div class="mb-3 batch-row">
                                        <label for="batchNoSelect" class="form-label">Batch No</label>
                                        <select id="batchNoSelect"
                                            class="form-select select2 batchNoSelect batch-input">
                                            <option value="" disabled selected>Batch No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
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
                                            <input class="form-check-input packaging-type pakagingType byItemBox" type="radio"
                                                id="byItemBox" name="packagingType" value="byItemBox">
                                            <label class="form-check-label" for="byItemBox">
                                                By Item Box
                                            </label>
                                        </div>
                                    </div>
                                </div>

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
        GetSKU();

        $('input[name="packagingType"]').change(function() {
            console.log($(this).val(), "jj")
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
        // $(document).on("click", ".add-row", function() {
        //     const rowIndex = $(".skuRow").length;
        //     // GetSKU();
        //     const newRow = `
        //         <div id="skuRows">
        //                 <div class="row">
        //                     <!-- Customer and Customer Type in one column -->
        //                     <div class="col-md-6">
        //                         <div class="mb-3">
        //                             <label for="customer" class="form-label">Select Customer</label>
        //                             <input class="form-control" id="customer" type="input" id="">
        //                         </div>


        //                         <div class="mb-3">
        //                             <label for="selectSku" class="form-label">Select SKU</label>
        //                             <select id="SKU" name="SKU" class="form-select select2 SKU sku-input">
        //                                 <option selected disabled>Select Product / SKU</option>
        //                             </select>
        //                         </div>
        //                     </div>

        //                     <!-- SKU and Batch in one column -->
        //                     <div class="col-md-6">

        //                         <div class="mb-3">
        //                             <label for="customerType" class="form-label">Select Customer Type</label>
        //                             <select class="form-select customer-type" id="customerType" required>
        //                                 <option value="" disabled selected>Select customer type</option>
        //                                 <option value="hospital">hospital</option>
        //                                 <option value="wholesale">wholesaler</option>
        //                                 <option value="retailer">retailer</option>
        //                             </select>
        //                         </div>

        //                         <div class="mb-3 batch-row">
        //                             <label for="batchNoSelect" class="form-label">Batch No</label>
        //                             <select id="batchNoSelect" class="form-select select2 batchNoSelect batch-input">
        //                                 <option value="" disabled selected>Batch No</option>
        //                             </select>
        //                         </div>
        //                     </div>

        //                                <div class="col-md-12">
        //             <div class="mb-3">
        //                 <label class="form-label">Select Packaging Type</label>
        //                 <div class="form-check">
        //                     <input class="form-check-input packaging-type" type="radio" id="byCarton_${rowIndex}"
        //                         name="packagingType_${rowIndex}" value="byCarton">
        //                     <label class="form-check-label" for="byCarton_${rowIndex}">
        //                         By Carton
        //                     </label>
        //                 </div>
        //                 <div class="form-check">
        //                     <input class="form-check-input packaging-type" type="radio" id="byItemBox_${rowIndex}"
        //                         name="packagingType_${rowIndex}" value="byItemBox">
        //                     <label class="form-check-label" for="byItemBox_${rowIndex}">
        //                         By Item Box
        //                     </label>
        //                 </div>
        //             </div>
        //         </div>
        //                     <!-- No of Cartons and No of Item Boxes -->
        //                                    <div class="col-md-6 mb-2">
        //             <div class="row">

        //                 <div class="col-md-6 cartonInput" id="" >
        //                     <div class="cartonCheckboxes" id="" >
        //                         <label class="form-label">Select Carton IDs</label>
        //                     </div>
        //                 </div>

        //                 <div class="col-md-6 itemBoxInput" id="itemBoxInput_${rowIndex}" style="display: none;">
        //                     <div id="itemBoxDropdown_${rowIndex}" class="itemBoxDropdown" style="display: none;">
        //                         <label for="itemBoxId_${rowIndex}" class="form-label">Select Item Box ID</label>
        //                         <div class="itemBoxDropdown" id="itemBoxCheckboxes_${rowIndex}"></div>
        //                     </div>
        //                 </div>

        //             </div>
        //         </div>

        //                 </div>
        //             </div>`;

        //     const $newRow = $(newRow);
        //     $("#skuRows").append($newRow);

        //     const $skuDropdown = $newRow.find(".SKU");

        //     GetSKU($skuDropdown);

        //     $skuDropdown.select2({
        //         placeholder: "Select an option",
        //         allowClear: true
        //     });
        //     $newRow.find('.packaging-type').change(function() {
        //         const rowID = $(this).closest(".skuRow")
        //             .index();
        //         console.log($(this).val(), rowID, "rowID")
        //         if ($(this).val() == 'byCarton') {
        //             $newRow.find(`.cartonInput`).show();
        //             $newRow.find(`.itemBoxInput`).hide();
        //             $newRow.find(`.cartonCheckboxes`).find('input[type="checkbox"]')
        //                 .prop('checked', false);
        //             $newRow.find(`.cartonCheckboxes`).find('input[type="number"]').val(
        //                 '');
        //         } else if ($(this).val() == 'byItemBox') {
        //             $newRow.find(`.itemBoxInput`).show();
        //             $newRow.find(`.cartonInput`).hide();
        //             $newRow.find(`.itemBoxCheckboxes`).find('input[type="checkbox"]')
        //                 .prop('checked', false);
        //             $newRow.find(`.itemBoxCheckboxes`).find('input[type="number"]')
        //                 .val('');
        //         }
        //     });

        // });

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
                    alert("Error loading cartons.");
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
    // document.addEventListener("DOMContentLoaded", function() {
    //     const productForm = document.getElementById("productForm");
    //     const submitButton = document.getElementById("sellSubmit");

    //     productForm.addEventListener("submit", async function(event) {

    //         event.preventDefault();

    //         const skuDataForRow = [];
    //         const skuRows = document.querySelectorAll(".skuRow"); // Ensure correct selection
    //         skuRows.forEach(row => {
    //             const skuInput = row.querySelector(".sku-input");
    //             const customerTypeSelect = row.querySelector(".customer-type");
    //             const packagingTypeRadio = row.querySelector(
    //                 'input[name="packagingType"]:checked');

    //             if (skuInput && customerTypeSelect && packagingTypeRadio) {
    //                 const skuObject = {
    //                     SKU: skuInput.value,
    //                     customerType: customerTypeSelect.value,
    //                     packagingType: packagingTypeRadio.value,
    //                 };
    //                 skuDataForRow.push(skuObject);
    //             } 
    //         });


    //         console.log(skuDataForRow, "skuDataForRow");
    //         // Gather SKU rows
    //         // const skuData = [];
    //         // const skuRows = document.querySelectorAll("#skuRows");
    //         // skuRows.forEach(row => {
    //         //     const skuObject = {
    //         //         SKU: row.querySelector(".sku-input").value,
    //         //         customerType: row.querySelector(".customer-type").value,
    //         //         packagingType: row.querySelector(
    //         //             'input[name="packagingType"]:checked') ? row.querySelector(
    //         //             'input[name="packagingType"]:checked').value : null,
    //         //     };
    //         //     skuData.push(skuObject);
    //         // });

    //         // console.log(skuData, "skuData")
    //         const batchData = [];
    //         const batchRows = document.querySelectorAll(".batch-row");
    //         batchRows.forEach(row => {
    //             const batchObject = {
    //                 batch: row.querySelector(".batch-input").value,

    //             };
    //             batchData.push(batchObject);
    //             const cartonInput = row.querySelector(".carton-input");

    //             if (cartonInput && cartonInput.checked) {
    //                 const cartonObject = {
    //                     carton: cartonInput
    //                         .value,
    //                 };
    //                 batchData.push(cartonObject);
    //             }
    //         });

    //         console.log(batchData, "batchData")
    //         const cartonData = [];
    //         const cartonRows = document.querySelectorAll(".carton-row");

    //         cartonRows.forEach(row => {
    //             const cartonInput = row.querySelector(".carton-input");

    //             if (cartonInput && cartonInput.checked) {
    //                 const cartonObject = {
    //                     carton: cartonInput
    //                         .value,
    //                 };
    //                 cartonData.push(cartonObject);
    //             }
    //         });

    //         console.log(cartonData, "cartonData 1")


    //         const cartonItemData = [];
    //         const cartonItemsRows = document.querySelectorAll(".carton-items-row");

    //         cartonItemsRows.forEach(row => {
    //             const cartonItemInput = row.querySelector(".carton-items-input");
    //             const quantityItemInput = row.querySelector(".quantity-items-input");

    //             if (cartonItemInput && quantityItemInput && quantityItemInput.value
    //                 .trim() !== "") {
    //                 const cartonObject = {
    //                     cartonItem: cartonItemInput.value,
    //                     quantityItem: quantityItemInput.value
    //                 };

    //                 cartonItemData.push(cartonObject);
    //             }
    //         });



    //         console.log(cartonItemData, "cartonItemData 2")
    //         const formObject = {
    //             customer: document.querySelector("#customer").value,
    //             skuData: skuDataForRow,
    //             batchData: batchData,
    //             cartonData: cartonData,
    //             cartonItemData: cartonItemData
    //         };

    //         console.log(formObject, "formObject");

    //         if (!formObject.customer || skuDataForRow.length === 0 || batchData.length ===
    //             0) {
    //             alert("Please fill in all required fields.");
    //             return;
    //         }

    //         // Disable submit button to prevent multiple submissions
    //         submitButton.disabled = true;

    //         try {
    //             const response = await fetch("{{ route('sellCounter.store') }}", {
    //                 method: "POST",
    //                 headers: {
    //                     'Content-Type': 'application/json',
    //                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
    //                 },
    //                 body: JSON.stringify(formObject),
    //             });

    //             if (response.ok) {
    //                 const result = await response.json();
    //                 toastr.success("Form submitted successfully!");
    //                 console.log(result);
    //                 // location.reload();

    //             } else {
    //                 const error = await response.json();
    //                 toastr.error("Error submitting form.");
    //                 console.error(error);
    //             }
    //         } catch (err) {
    //             console.error("Fetch error:", err);
    //             toastr.error("An error occurred. Please try again.");
    //         } finally {
    //             // Re-enable the submit button
    //             submitButton.disabled = false;
    //         }
    //     });
    // });

    document.addEventListener("DOMContentLoaded", function() {
        const productForm = document.getElementById("productForm");
        const submitButton = document.getElementById("sellSubmit");

        productForm.addEventListener("submit", async function(event) {
            event.preventDefault();

            const skuDataForRow = [];
            const batchData = {}; 

         
            const skuRows = document.querySelectorAll(".skuRow");
            skuRows.forEach(row => {
                const skuInput = row.querySelector(".sku-input");
                const customerTypeSelect = row.querySelector(".customer-type");
                const packagingTypeRadio = row.querySelector(
                    'input[name="packagingType"]:checked'
                );

                if (skuInput && customerTypeSelect && packagingTypeRadio) {
                    const skuValue = skuInput.value;
                    const customerType = customerTypeSelect.value;
                    const packagingType = packagingTypeRadio.value;

                    skuDataForRow.push({
                        SKU: skuValue,
                        customerType: customerType,
                        packagingType: packagingType
                    });

                  
                    const batchInput = row.querySelector(".batch-input").value;

                  
                    if (!batchData[batchInput]) {
                        batchData[batchInput] = {
                            sku: [],
                            cartons: [],
                            numCartons: 0,
                            cartonData: [],
                            cartonItemsData: []
                        };
                    }

                  
                    batchData[batchInput].sku.push({
                        SKU: skuValue,
                        customerType: customerType,
                        packagingType: packagingType
                    });
                }
            });

           
            const batchRows = document.querySelectorAll(".skuRow");
            batchRows.forEach(row => {
                const batchInput = row.querySelector(".batch-input").value;

                if (!batchData[batchInput]) {
                    batchData[batchInput] = {
                        sku: [],
                        cartons: [],
                        numCartons: 0,
                        cartonData: [],
                        cartonItemsData: [],
                        packagingTypes: [] 
                    };
                }

                const packagingTypeInputs = row.querySelectorAll(".packaging-type");
    if (!Array.isArray(batchData[batchInput].packagingTypes)) {
        batchData[batchInput].packagingTypes = [];
    }

    packagingTypeInputs.forEach(packagingInput => {
        if (packagingInput.checked) {
            batchData[batchInput].packagingTypes.push(packagingInput.value);
        }
    });
                // Handle SKUs
                const skuInputs = row.querySelectorAll(".sku-input");
                skuInputs.forEach(skuInput => {
                    if (skuInput.value.trim() !== "") {
                        batchData[batchInput].sku.push(skuInput.value);
                    }
                });

                // Handle cartons
                const cartonInputs = row.querySelectorAll(".carton-input");
                cartonInputs.forEach(cartonInput => {
                    if (cartonInput.checked) {
                        batchData[batchInput].cartons.push(cartonInput.value);
                        batchData[batchInput].cartonData.push(cartonInput.value);
                        batchData[batchInput].numCartons++;
                    }
                });

                // Handle carton items
                const cartonItemsRows = row.querySelectorAll(".carton-items-row");
                cartonItemsRows.forEach(cartonItemRow => {
                    const cartonItemInput = cartonItemRow.querySelector(
                        ".carton-items-input");
                    const quantityItemInput = cartonItemRow.querySelector(
                        ".quantity-items-input");

                    if (cartonItemInput && quantityItemInput && quantityItemInput
                        .value.trim() !== "") {
                        batchData[batchInput].cartonItemsData.push({
                            cartonItem: cartonItemInput.value,
                            quantityItem: quantityItemInput.value
                        });
                    }
                });
            });


            const formObject = {
                customer: document.querySelector("#customer").value,
                skuData: skuDataForRow,
                batchData: batchData
            };

            console.log("Form Object:", formObject);

            if (!formObject.customer || skuDataForRow.length === 0 || Object.keys(batchData)
                .length === 0) {
                alert("Please fill in all required fields.");
                return;
            }

            // Disable submit button to prevent multiple submissions
            submitButton.disabled = true;

            try {
                const response = await fetch("{{ route('sellCounter.store') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(formObject),
                });

                if (response.ok) {
                    const result = await response.json();
                    toastr.success("Form submitted successfully!");
                    console.log(result);
                    // location.reload();
                } else {
                    const error = await response.json();
                    toastr.error(`Error: ${error.error}<br>File: ${error.file}<br>Line: ${error.line}`);
                    // toastr.error("Error submitting form.");
                    // toastr.error(error);
                }
            } catch (err) {
               
                toastr.error("An error occurred. ", err.error);
            } finally {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });
</script>
</body>

</html>
