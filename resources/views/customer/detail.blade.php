@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Stock List']); ?>
    @include('partials.link')
    @include('partials.head-css')
    <style>
        .stock-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .page-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .item-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
        }

        .detail-value {
            font-size: 15px;
            color: #333;
        }

        .stock-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
        }

        .stock-table th {
            background: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #444;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap;
        }

        .stock-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #333;
            font-size: 14px;
        }

        .stock-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 6px;
            border: 1px solid #eee;
        }

        .secondary-text {
            display: block;
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }

        @media (max-width: 768px) {
            .stock-container {
                padding: 15px;
            }

            .item-details-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

@include('partials.body')

<div id="layout-wrapper">
    @include('partials.topbar')
    @include('partials.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Customer Details - {{ $customer->name }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h5>Customer Information</h5>
                                        <p><strong>Phone:</strong> {{ $customer->phone_number }}</p>
                                        <p><strong>Address:</strong> {{ $customer->address }}</p>
                                        <p><strong>Credit Limit:</strong> {{ $customer->credit_limit }}</p>
                                        <p><strong>Payment Days:</strong> {{ $customer->payment_days }}</p>
                                        <p><strong>Type:</strong> {{ $customer->type_of_customer }}</p>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Particulars</th>
                                                <th>Amount Debit</th>
                                                <th>Amount Credit</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>Opening Balance</td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $transactions->first()->balance ?? 0 }}</td>
                                            </tr>
                                            @foreach ($transactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->created_at }}</td>
                                                    <td>
                                                        @if ($transaction->invoice_number)
                                                            Purchase ({{ $transaction->invoice_number }})
                                                        @else
                                                            Payment Made
                                                        @endif
                                                    </td>
                                                    <td>{{ $transaction->credit_limit }}</td>
                                                    <td>{{ $transaction->amount_credit }}</td>
                                                    <td>{{ (int)$transaction->credit_limit - (int)$transaction->amount_credit}}</td>
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
        </div>
    </div>

    @include('partials.right-sidebar')
    @include('partials.vendor-scripts')
    @include('partials.script')
    </body>

    </html>
