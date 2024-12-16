@include('partials.session')
@include('partials.main')


<head>

    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>


    @include('partials.link')
    @include('partials.head-css')
    <style>
        .sku-row {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .sku-row .row {
            margin-bottom: 10px;
        }

        .form-label {
            font-weight: bold;
        }

        .add-row,
        .remove-row {
            cursor: pointer;
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
                    <!-- Form Title -->
                    <h2 class="mb-4">Create Invoice</h2>

                    <!-- Invoice Form -->
                    <form>
                        <!-- Customer and Customer Type Section -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="selectCustomer" class="form-label">Select Customer</label>
                                <select class="form-select" id="selectCustomer">
                                    <option selected>Select Vendor</option>
                                    <option value="vendor1">Vendor 1</option>
                                    <option value="vendor2">Vendor 2</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="selectCustomerType" class="form-label">Select Customer Type</label>
                                <select class="form-select" id="selectCustomerType">
                                    <option selected>Select Customer Type</option>
                                    <option value="type1">Retail</option>
                                    <option value="type2">Wholesale</option>
                                </select>
                            </div>
                        </div>

                        <!-- SKU and Batch Section -->
                        <div id="skuRows">
                            <div class="sku-row">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="skuNumber1" class="form-label">Select SKU No</label>
                                        <select class="form-select" id="skuNumber1">
                                            <option selected>SKU12345</option>
                                            <option value="sku2">SKU67890</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="batchNumber1" class="form-label">Select Batch</label>
                                        <select class="form-select" id="batchNumber1">
                                            <option selected>Batch123</option>
                                            <option value="batch2">Batch456</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label d-block">Add More</label>
                                        <button type="button" class="btn btn-outline-primary add-row">+</button>
                                    </div>
                                </div>

                                <!-- Package Type Selection -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Choose Package Type</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="packageType1"
                                                id="carton1" checked>
                                            <label class="form-check-label" for="carton1">By Carton</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="packageType1"
                                                id="itemBox1">
                                            <label class="form-check-label" for="itemBox1">By Item Box</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label for="noOfCartons1" class="form-label">Provide No Of Cartons</label>
                                        <input type="number" class="form-control" id="noOfCartons1" placeholder="15">
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label for="noOfItems1" class="form-label">Provide No Of Item Boxes</label>
                                        <input type="number" class="form-control" id="noOfItems1" placeholder="12">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Another Row -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary">Add Another SKU</button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                    <!-- Invoice Details Table -->
                    <h3 class="mt-5">Invoice Details</h3>
                    <table class="table table-bordered mt-3">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice No</th>
                                <th>Vendor Name</th>
                                <th>SKU</th>
                                <th>Batch</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>12345</td>
                                <td>Vendor Name 1</td>
                                <td>SKU12345</td>
                                <td>Batch123</td>
                                <td>15</td>
                                <td>Approved</td>
                                <td><button class="btn btn-sm btn-primary">Edit</button></td>
                            </tr>
                            <tr>
                                <td>12346</td>
                                <td>Vendor Name 2</td>
                                <td>SKU67890</td>
                                <td>Batch456</td>
                                <td>20</td>
                                <td>Not Approved</td>
                                <td><button class="btn btn-sm btn-danger">Delete</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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

<script>
    $(document).ready(function() {
        // Add a new row dynamically when "+" is clicked
        $(document).on("click", ".add-row", function() {
            const newRow = `
          <div class="sku-row mb-3">
            <div class="row">
              <div class="col-md-4">
                <label for="skuNumber" class="form-label">Select SKU No</label>
                <select class="form-select" name="skuNumber[]">
                  <option selected>SKU12345</option>
                  <option value="sku2">SKU67890</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="batchNumber" class="form-label">Select Batch</label>
                <select class="form-select" name="batchNumber[]">
                  <option selected>Batch123</option>
                  <option value="batch2">Batch456</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label d-block">Remove</label>
                <button type="button" class="btn btn-outline-danger remove-row">-</button>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3">
                <label class="form-label">Choose Package Type</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="packageType[]" value="carton" checked>
                  <label class="form-check-label">By Carton</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="packageType[]" value="itemBox">
                  <label class="form-check-label">By Item Box</label>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label">No Of Cartons</label>
                <input type="number" class="form-control" name="noOfCartons[]" placeholder="15">
              </div>
              <div class="col-md-3">
                <label class="form-label">No Of Item Boxes</label>
                <input type="number" class="form-control" name="noOfItems[]" placeholder="12">
              </div>
            </div>
          </div>`;
            $("#skuRows").append(newRow);
        });

        // Remove row functionality
        $(document).on("click", ".remove-row", function() {
            $(this).closest(".sku-row").remove();
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
