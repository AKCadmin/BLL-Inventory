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
                {{-- <div class="container my-5">
                    <form action="{{ route('stock.store') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="selectSku" class="form-label">Select SKU</label>
                            <select id="SKU" name="SKU" class="form-select select2">
                                <option selected disabled>Select Product / SKU</option>
                                <!-- Populate with products -->
                            </select>
                        </div>

                        <!-- Add Purchase -->
                        <h4>Add Purchase</h4>

                        <!-- Dynamic Rows for Batch Info -->
                        <div id="batchRows" class="container mb-3">
                            <!-- Placeholder for dynamic rows -->
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" id="addRow" class="btn btn-primary">Add Row</button>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success mt-2">Submit</button>
                    </form>

                    




                </div> --}}
                <div id="purchaseContainer">
                    <div class="row border p-3 rounded mb-3">
                        <div class="col-md-3 mb-2">
                            <label for="batchNo" class="form-label">Batch No.</label>
                            <input type="text" class="form-control batchNo" id="batchNo"
                                placeholder="Enter Batch No.">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="manufacturingDate" class="form-label">Manufacturing Date</label>
                            <input type="date" class="form-control manufacturingDate" id="manufacturingDate">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control expiryDate" id="expiryDate">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="basePrice" class="form-label">Base Price</label>
                            <input type="number" class="form-control basePrice" id="basePrice"
                                placeholder="Enter Base Price">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="noOfCartons" class="form-label">No Of Cartons</label>
                            <input type="number" class="form-control noOfCartons" id="noOfCartons"
                                placeholder="Enter No Of Cartons">
                        </div>
                        <div class="col-md-12 mb-3" id="cartonRows"></div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-info" id="">Process Cartons</button>
                        </div>
                    </div>
                </div>
                <button id="addRow" type="button" class="btn btn-primary mt-3">Add Row</button>
                <button type="submit" id="updateButton" class="btn btn-success mt-3">Update</button>

            </div>
        </div>
    </div>


    @include('partials.right-sidebar')
    @include('partials.vendor-scripts')
    @include('partials.script')
    <script>
        $(document).ready(function() {
            function validateManufacturingDate(manufacturingDate) {
                const today = new Date();
                const selectedDate = new Date(manufacturingDate);

                // Check if the date is in the future
                if (selectedDate > today) {
                    return true; // Future date not allowed
                }
                return false; // Valid date
            }

            // Validate Expiry Date
            function validateExpiryDate(expiryDate, manufacturingDate) {
                const selectedExpiryDate = new Date(expiryDate);
                const selectedManufacturingDate = new Date(manufacturingDate);

                // Expiry date should not be before the manufacturing date
                if (selectedExpiryDate < selectedManufacturingDate) {
                    return true; // Invalid expiry date
                }
                return false; // Valid expiry date
            }

            function renderCartons(batchId, existingCartons, numNewCartons) {
                let cartonRowsContainer = $(`#cartonRows_${batchId}`);
                cartonRowsContainer.empty(); // Clear previous carton rows

                // Render existing cartons
                existingCartons.forEach((carton, index) => {
                    let cartonRow = $(`
                <div class="row cartonRow mb-3">
                    <div class="col-md-4">
                        <label class="form-label">No. of Items Inside</label>
                        <input type="number" class="form-control noOfItemsInside" name="carton[${index}][no_of_items_inside]" value="${carton.no_of_items_inside}" >
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Missing Items</label>
                        <input type="number" class="form-control missingItems" name="carton[${index}][missing_items]" value="${carton.missing_items}" >
                    </div>
                </div>
            `);
                    cartonRowsContainer.append(cartonRow);
                });

                // Render new cartons
                let currentCartonCount = existingCartons.length;
                for (let i = 1; i <= numNewCartons; i++) {
                    let newCartonIndex = currentCartonCount + i;
                    let cartonRow = $(`
                <div class="row cartonRow mb-3">
                    <div class="col-md-4">
                        <label class="form-label">No. of Items Inside</label>
                        <input type="number" class="form-control noOfItemsInside" name="carton[${newCartonIndex}][no_of_items_inside]" placeholder="Enter no of items">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Missing Items</label>
                        <input type="number" class="form-control missingItems" name="carton[${newCartonIndex}][missing_items]" placeholder="Enter missing items">
                    </div>
                </div>
            `);
                    cartonRowsContainer.append(cartonRow);
                }
            }

            // Function to render batch data
            function renderBatchData(batchData) {
                let purchaseContainer = $('#purchaseContainer');
                purchaseContainer.empty();

                if (!Array.isArray(batchData)) {
                    batchData = Object.values(batchData);
                }

                batchData.forEach((batch) => {
                    let batchRow = $(`
                <div class="row border p-3 rounded mb-3">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Batch No.</label>
                        <input type="text" class="form-control batchNo" value="${batch.batch_number}" >
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Manufacturing Date</label>
                        <input type="date" class="form-control manufacturingDate" value="${batch.manufacturing_date}" >
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" class="form-control expiryDate" value="${batch.expiry_date}" >
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Base Price</label>
                        <input type="number" class="form-control basePrice" value="${batch.base_price}" >
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Exchange Rate</label>
                        <input type="number" class="form-control exchangeRate" value="${batch.exchange_rate}" >
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Buy Price</label>
                        <input type="number" class="form-control buyPrice" value="${batch.buy_price}" >
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Add New Cartons</label>
                        <input type="number" class="form-control" id="newCartons_${batch.batch_id}" placeholder="Enter no. of new cartons">
                    </div>
                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" id="processCartonsBtn_${batch.batch_id}">Process Cartons</button>
                    </div>
                    <div class="col-md-12 mb-3 cartonRows" id="cartonRows_${batch.batch_id}">
                        <h5>Cartons for Batch ${batch.batch_number}</h5>
                    </div>
                </div>
            `);

                    purchaseContainer.append(batchRow);

                    // Render initial cartons
                    renderCartons(batch.batch_id, batch.cartons, 0);

                    // Attach event listener to the "Process Cartons" button
                    $(`#processCartonsBtn_${batch.batch_id}`).on('click', function() {
                        processCartons(batch.batch_id, batch.cartons);
                    });

                    batchRow.find('.manufacturingDate').attr('max', new Date().toISOString().split('T')[0]);

                    // When Manufacturing Date changes
                    batchRow.find('.manufacturingDate').on('change', function() {
                        const manufacturingDate = $(this).val();
                        const expiryDateInput = batchRow.find('.expiryDate');

                        // If Manufacturing Date is invalid (future date), disable the field
                        if (validateManufacturingDate(manufacturingDate)) {
                            $(this).val(''); // Clear invalid value
                            expiryDateInput.prop('disabled', true); // Disable Expiry Date
                        } else {
                            expiryDateInput.prop('disabled', false); // Enable Expiry Date
                            // Set the minimum value for Expiry Date to Manufacturing Date
                            if (manufacturingDate) {
                                expiryDateInput.attr('min', manufacturingDate);
                            }
                        }
                    });

                    // When Expiry Date changes
                    batchRow.find('.expiryDate').on('change', function() {
                        const expiryDate = $(this).val();
                        const manufacturingDate = batchRow.find('.manufacturingDate').val();

                        // If Expiry Date is invalid (before Manufacturing Date), disable the field
                        if (validateExpiryDate(expiryDate, manufacturingDate)) {
                            $(this).val(''); // Clear invalid value
                        }
                    });

                    batchRow.find('.basePrice, .exchangeRate').on('input', function() {
                        const basePrice = parseFloat(batchRow.find('.basePrice').val()) || 0;
                        const exchangeRate = parseFloat(batchRow.find('.exchangeRate').val()) || 0;
                        const buyPrice = basePrice * exchangeRate;

                        batchRow.find('.buyPrice').val(buyPrice.toFixed(
                        2)); // Set the calculated buy price
                    });
                });
            }


            function processAndUpdateStock(batchId) {
                const numNewCartons = document.getElementById(`newCartons_${batchId}`).value;

                // Validate input for new cartons
                if (isNaN(numNewCartons) || numNewCartons <= 0) {
                    alert("Please enter a valid number for new cartons.");
                    return;
                }

                const existingCartons = batchData[batchId].cartons;
                let updatedCartons = [...existingCartons];

                // Generate new cartons based on user input
                for (let i = 1; i <= numNewCartons; i++) {
                    let newCartonIndex = existingCartons.length + i;
                    updatedCartons.push({
                        carton_number: `Carton-${newCartonIndex}`,
                        no_of_items_inside: 100, // Default value or user input
                        missing_items: 0, // Default value or user input
                    });
                }

                // Update stock and send request to backend
                updateStock(batchId, updatedCartons);
            }

            $('#updateButton').click(function() {
                const batches = [];

                $('#purchaseContainer .row.border').each(function() {
                    const batch = {
                        batch_no: $(this).find('.batchNo').val(),
                        manufacturing_date: $(this).find('.manufacturingDate').val(),
                        expiry_date: $(this).find('.expiryDate').val(),
                        base_price: $(this).find('.basePrice').val(),
                        exchange_rate: $(this).find('.exchangeRate').val(),
                        buy_price: $(this).find('.buyPrice').val(),
                        cartons: [],
                    };

                    $(this).find('.cartonRows .row').each(function() {
                        const carton = {
                            carton_no: $(this).find('.cartonNo').val(),
                            no_of_items_inside: $(this).find('.noOfItemsInside').val(),
                            missing_items: $(this).find('.missingItems').val(),
                        };
                        batch.cartons.push(carton);
                    });

                    batches.push(batch);
                });

                console.log(batches, "batches")
                fetch('{{ route('stock.batch.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            batches
                        }),
                    })
                    .then(response => response.json())
                    .then(responseData => {
                        if (responseData.success) {
                            toastr.success('Batches updated successfully!');
                            window.location.href =
                            '{{ route('stock.list') }}';
                        } else {
                            toastr.error('Error: ' + responseData.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('An error occurred while updating the batches.');
                    });


            });


            // Function to process cartons when the button is clicked
            function processCartons(batchId, existingCartons) {
                // Get the number of new cartons from the input
                let numNewCartons = $(`#newCartons_${batchId}`).val();

                // Validate if input is a valid number
                if (isNaN(numNewCartons) || numNewCartons <= 0) {
                    alert("Please enter a valid number for new cartons.");
                    return;
                }

                // Call renderCartons to append the new cartons
                renderCartons(batchId, existingCartons, parseInt(numNewCartons));

                // Optionally clear the input after processing
                $(`#newCartons_${batchId}`).val('');
            }

            const batchData = @json($groupedData);

            // Render batch data on page load
            renderBatchData(batchData);
        });
    </script>

    </body>

    </html>
