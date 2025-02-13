@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'brand Management']); ?>
    @include('partials.link')
    @include('partials.head-css')
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .table td {
            vertical-align: top;
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

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Brand - {{ $brand->name }}</h4>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Address:</strong> {{ $brand->address }}<br>
                                        <strong>Contact:</strong> {{ $brand->contact_person }} ({{ $brand->phone_no }})
                                    </div>
                                    <div>
                                        @php
                                                $currentBalance = 0; // Initialize balance
                                            @endphp
                                            @foreach ($transactions as $transaction)
                                                @php
                                                    $currentBalance += $transaction->amount; // Update balance
                                                @endphp
                                            @endforeach
                                        <strong>Current Balance:</strong> {{ number_format($currentBalance ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Credit</th>
                                                <th>Debit</th>                                               
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $balance = 0; // Initialize balance
                                            @endphp
                                            @foreach ($transactions as $transaction)
                                                @php
                                                    $balance += $transaction->amount; // Update balance
                                                @endphp
                                                <tr>
                                                    <td>{{ date('Y-m-d', strtotime($transaction->date)) }}</td>
                                                    <td>{{ $transaction->invoice_no }}</td>
                                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                                    <td></td>                              
                                                    <td>{{ number_format($balance, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Page-content -->

        @include('partials.footer')
    </div> <!-- end main content -->

</div>

@include('partials.right-sidebar')
@include('partials.vendor-scripts')
@include('partials.script')
</body>

</html>
