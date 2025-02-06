<style>
    /* General Logo Styling */
    .logo-image {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.2));
    }

    /* Hover Effect */
    .logo-image:hover {
        transform: scale(1.05);
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* Align the logo properly */
    .navbar-brand-box {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .logo-lg {
            display: none;
        }

        .logo-sm {
            display: block;
        }
    }

    /* Style for the square dropdown */
    #organization-filter {
        border: 2px solid #091017;
        border-radius: 4px;
        /* Slight rounding for a modern square look */
        background-color: #f8f9fa;
        /* Light gray background */
        padding: 10px 12px;
        /* Comfortable padding for better usability */
        font-size: 14px;
        /* Clean and readable font size */
        color: #495057;
        /* Neutral text color */
        height: 42px;
        /* Consistent height */
        outline: none;
        /* Removes focus outline */
        transition: all 0.3s ease;
        /* Smooth transition for hover effects */
    }

    #organization-filter:hover {
        background-color: #e9ecef;
        /* Slight hover effect for better UX */
        border-color: #0056b3;
        /* Darker blue on hover */
    }

    #organization-filter:focus {
        border-color: #0056b3;
        /* Focus border color */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        /* Subtle focus glow */
    }

    /* Input group for proper alignment */
    /* .input-group {
        width: 100%;
        /* Full width */
    max-width: 300px;
    /* Set a max width for the dropdown */
    margin: 0 auto;
    /* Center align (optional) */
    }

    */
</style>

{{-- <style>
    #organizationModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .modal-title {
        margin: 0;
        color: #2c3e50;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .organization-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .organization-item {
        padding: 0.75rem;
        border: 1px solid #eee;
        margin-bottom: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .organization-item:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .organization-item label {
        display: flex;
        align-items: center;
        margin: 0;
        cursor: pointer;
        color: #495057;
        font-size: 1rem;
    }

    .organization-item input[type="radio"] {
        margin-right: 12px;
        width: 18px;
        height: 18px;
        accent-color: #0d6efd;
    }

    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #0d6efd;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
    }
</style> --}}

<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">

            <div class="navbar-brand-box">
                <a href="{{ route('home') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-image" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-image"
                            height="30">
                    </span>
                </a>

                <a href="{{ route('home') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-image"
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="logo-image"
                            height="30">
                    </span>
                </a>
            </div>


            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            {{-- <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form> --}}
            @php
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $organizations = App\Models\Organization::all();
            @endphp
            @if (auth()->user()->role == 1)
                <form class="app-search d-none d-lg-block" id="organizationSwitchForm" method="POST">
                    @csrf
                    <div class="input-group">
                        <select id="organization-filter" name="organization" class="form-control custom-select">
                            <option value="">Select Organization</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ str_replace('_', ' ', $organization->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            @endif


        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="bx bx-fullscreen"></i>
                </button>
            </div> --}}

            {{-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="bx bx-bell bx-tada"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small" key="t-view-all"> View All</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="bx bx-cart"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" key="t-your-order">Your order is placed</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                                                key="t-min-ago">3
                                                min ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ asset('assets/images/users/avatar-3.jpg') }}"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-simplified">It will seem like simplified English.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                                                key="t-hours-ago">1 hours ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="bx bx-badge-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" key="t-shipped">Your item is shipped</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">If several languages coalesce the grammar
                                        </p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                                                key="t-min-ago">3 min ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ asset('assets/images/users/avatar-4.jpg') }}"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-occidental">As a skeptical Cambridge friend of mine
                                            occidental.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                                                key="t-hours-ago">1 hours ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span>
                        </a>
                    </div>
                </div>
            </div> --}}

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ Auth::user()->name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                {{-- <input type="text" id="appUrl" value="{{ env('APP_URL') }}"> --}}
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i>
                        <span key="t-profile">Profile</span></a>
                    <a class="dropdown-item" href="#"><i
                            class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">My
                            Wallet</span></a>
                    <a class="dropdown-item d-block" href="#"><span
                            class="badge bg-success float-end">11</span><i
                            class="bx bx-wrench font-size-16 align-middle me-1"></i> <span
                            key="t-settings">Settings</span></a>
                    <a class="dropdown-item" href="#"><i
                            class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">Lock
                            screen</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i
                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                            key="t-logout">Logout</span></a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>

        </div>


    </div>

</header>
{{-- <div id="organizationModal" class="modal" style="display: none">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Select Organization</h5>
        </div>
        <div class="modal-body">
            @php
                config(['database.connections.pgsql.database' => env('DB_DATABASE')]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
                $organizations = App\Models\Organization::all();
            @endphp

            @if (auth()->user()->role == 1)
                <div class="organization-list">
                    @foreach ($organizations as $organization)
                        <div class="organization-item">
                            <label for="organization_{{ $organization->id }}">
                                <input type="radio" name="selectedOrganization"
                                    id="organization_{{ $organization->id }}" value="{{ $organization->id }}">
                                {{ str_replace('_', ' ', $organization->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="closeModalBtn">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveSelectionBtn">Confirm</button>
        </div>
    </div>
</div> --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#organization-filter').val("");
        $('#organization-filter').change(function() {
            let organizationId = $(this).val(); // Get selected value
            if (organizationId) {
                $.ajax({
                    url: "{{ route('switch.organization') }}", // Your Laravel route
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}", // CSRF token
                        organization: organizationId
                    },
                    success: function(response) {

                    },
                    error: function(xhr) {
                        let error = xhr.responseJSON ? xhr.responseJSON.message :
                            'An error occurred';
                        $('#errorMessage').text(error).show(); // Show error message
                        $('#successMessage').hide();
                    }
                });
            }
        });
    });
</script>
