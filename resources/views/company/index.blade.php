@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Company Management']); ?>
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
                <?php includeFileWithVariables('partials/page-title.php', ['pagetitle' => 'Company List', 'title' => 'Manage Companies']); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="RoleTableHeader">
                                    <h4 class="card-title"></h4>

                                    <a href="#" class="btn btn-primary waves-effect waves-light btn-sm"
                                        id="openModalBtn">Create a company
                                        <i class="mdi mdi-arrow-right ms-1"></i>
                                    </a>


                                </div><br>
                                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Email</th>
                                            <th>Phone no</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($companies as $company)
                                            <tr>
                                                <td>{{ $company->id }}</td>
                                                <td>{{ $company->name }}</td>
                                                <td>{{ $company->email }}</td>
                                                <td>{{ $company->phone }}</td>
                                                <td>{{ $company->status ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-warning edit-company-btn" data-id="{{ $company->id }}">Edit</a>
                                                    <button class="btn btn-sm btn-danger delete-company-btn" data-id="{{ $company->id }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Company Modal -->
                <div id="companyModal" class="modal">
                    <div class="container" style="margin-top:50px;">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary-subtle">
                                        <div class="row" id="popup_row">
                                            <div class="col-7">
                                                <div class="text-primary p-4">
                                                    <h5 class="text-primary" id="modal_header">Add New Company</h5>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <span class="close">×</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="p-2">
                                            <!-- Validation Errors -->
                                            <div id="errorAlert">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        {{ $errors->first() }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Success Message -->
                                            <div id="successAlert">
                                                @if (session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <form id="companyForm" method="post" action="{{ route('company.store') }}">
                                                @csrf
                                                <input type="hidden" class="form-control" id="company_id"
                                                    name="company_id" value="">

                                                <div class="mb-3">
                                                    <label for="company_name" class="form-label">Company Name</label>
                                                    <input type="text" class="form-control" id="company_name"
                                                        name="company_name" required placeholder="Enter Company Name">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="company_address" class="form-label">Company
                                                        Address</label>
                                                    <input type="tel" class="form-control" id="company_address"
                                                        name="company_address" required
                                                        placeholder="Enter Company Address">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="company_email" class="form-label">Company Email</label>
                                                    <input type="email" class="form-control" id="company_email"
                                                        name="company_email" required placeholder="Enter Company Email">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone_no" class="form-label">Phone No</label>
                                                    <input type="number" class="form-control" id="phone_no"
                                                        name="phone_no" required placeholder="Enter Company Phone No">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="company_status" class="form-label">Status</label>
                                                    <select class="form-select" id="company_status"
                                                        name="company_status" required>
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="text-end">
                                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                                        type="submit">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- End Page-content -->

        @include('partials.footer')
    </div> <!-- end main content -->

</div> 

@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')
<script>
    $(document).ready(function () {
       
        $('#openModalBtn').on('click', function () {
            $('#companyModal').show();
            $('#company_id').val('');
            $('#companyForm')[0].reset();
            $('#modal_header').text('Add New Company');
        });

       
        $('.close').on('click', function () {
            $('#companyModal').hide();
        });

        
        $('#companyForm').on('submit', function (e) {
            e.preventDefault();

            let companyId = $('#company_id').val();
            let formData = $(this).serialize();
            let url = companyId
                ? '{{ route('company.update', ':id') }}'.replace(':id', companyId)
                : '{{ route('company.store') }}';
            let requestType = companyId ? 'PUT' : 'POST';

            ajaxRequest(url, requestType, formData, 
                function (response) {
                    if (response.success) {
                        toastr.success('Company saved successfully!');
                        $('#companyModal').hide();
                        location.reload();
                        loadCompanies();
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                },
                function () {
                    toastr.error('An error occurred while processing the request.');
                }
            );
        });

       
        $('#datatable').on('click', '.edit-company', function () {
            let companyId = $(this).data('id');
            let url = `{{ route('company.editCompany', '') }}/${companyId}`;

            ajaxRequest(url, 'GET', {}, 
                function (response) {
                    if (response.success) {
                        $('#companyModal').show();
                        $('#company_id').val(response.company.id);
                        $('#company_name').val(response.company.name);
                        $('#company_address').val(response.company.address);
                        $('#company_email').val(response.company.contact_email);
                        $('#phone_no').val(response.company.phone_no);
                        $('#company_status').val(response.company.status);
                        $('#modal_header').text('Edit Company');
                    } else {
                        toastr.error('Error: ' + response.message);
                    }
                }
            );
        });

       
        $('#datatable').on('click', '.delete-company', function () {
            let companyId = $(this).data('id');
            let appUrl = $("#appUrl").val();
            let url = appUrl + '/company/' + companyId;

            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Product deleted successfully!');
                            loadCompanies();
                        } else {
                            toastr.error('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        toastr.error(
                            'An error occurred while trying to delete the product.');
                    }
                });
            }
        });

        function loadCompanies() {
    ajaxRequest('{{ route('company.getData') }}', 'GET', {}, function (response) {
        let rows = '';
        response.companies.forEach(function (company) {
            const charLimit = 10; 
            const truncatedAddress =
                company.address.length > charLimit
                    ? company.address.substring(0, charLimit) + '...'
                    : company.address;

            rows += `
                <tr>
                    <td>${company.id}</td>
                    <td>${company.name}</td>
                    <td title="${company.address}">${truncatedAddress}</td>
                    <td>${company.contact_email}</td>
                    <td>${company.phone_no}</td>
                    <td>${company.status ? 'Active' : 'Inactive'}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-company" data-id="${company.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-company" data-id="${company.id}">Delete</button>
                    </td>
                </tr>
            `;
        });
        $('#datatable tbody').html(rows);
    });
}

        loadCompanies();
    });
</script>

</body>

</html>
