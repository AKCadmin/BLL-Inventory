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
            <div class="stock-container">
                <h1 class="page-title">Stock Details</h1>
                
                <!-- Item Details Grid -->
                <div class="item-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Item Name</span>
                        <span class="detail-value">{{ $product->name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Unit</span>
                        <span class="detail-value">{{ $product->unit }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Batch No.</span>
                        <span class="detail-value">{{$data[0]->batch_number}}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Expiry</span>
                        <span class="detail-value">{{$data[0]->expiry_date}}</span>
                    </div>
                </div>

                <!-- Stock Table -->
                <div class="table-container">
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Unit Per Carton</th>
                                <th>Qty In</th>
                                <th>Qty Out</th>
                                <th>Closing Balance</th>
                                <th>
                                    Stock Value
                                    <span class="secondary-text">(Balance × Purchase Price)</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $item)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $item->no_of_units }}</td>
                                <td>{{ $item->purchase_quantity ?? '' }}</td>
                                <td>{{ $item->sold_cartons ?? '' }}</td>
                                <td></td>
                                <td></td>
                                {{-- <td>{{ $item->batch_quantity }}</td> --}}
                                {{-- <td>₹{{ number_format($item->batch_quantity * $item->buy_price, 2) }}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('partials.right-sidebar')
    @include('partials.vendor-scripts')
    @include('partials.script')
</body>
</html>