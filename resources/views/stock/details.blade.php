@include('partials.session')
@include('partials.main')

<head>
    <?php includeFileWithVariables('partials/title-meta.php', ['title' => 'Data Tables']); ?>
    @include('partials.link')
    @include('partials.head-css')

    <style>
        .stock-details-title {
            font-size: 2rem;
            /* color: white; */
            margin-bottom: 1.5rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 1rem;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Add spacing between tables */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .section-header {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .provisional-profit {
            font-weight: bold;
            background-color: #d4edda;
        }

        .batch-container {
            /* Style for each batch container */
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
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
                <div class="stock-details-header">
                    <h1 class="stock-details-title">Stock Details</h1>
                    <h3>Supplier Name: {{ $brand->name }}</h3>
                    <h3>Product Name: {{ $product->name }}</h3>
                    <h3>Created At: {{ \Carbon\Carbon::parse($createdAt)->format('d M Y') }}</h3>
                </div>
                <div class="row">

                    @foreach ($data as $index => $item)
                        <div class="col-6">

                            <div class="batch-container">
                                <table border="1" cellpadding="8" cellspacing="0">
                                    <thead>
                                        <tr class="section-header">
                                            <th colspan="3">Batch Number: {{ $item->batch_number }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="background-color: #e0ffe0; font-weight: bold;">
                                            <td colspan="3">Purchase</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;No of Unit per cartoon</td>
                                            <td colspan="2">{{ $item->no_of_units }}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Number of cartoons</td>
                                            <td colspan="2">{{ $item->purchase_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Base Price</td>
                                            <td colspan="2">₹ {{ number_format($item->base_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Buy Price</td>
                                            <td colspan="2">₹ {{ number_format($item->buy_price, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Retail Price</td>
                                            <td colspan="2">₹ {{ number_format($item->retail_price, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Wholesale Price</td>
                                            <td colspan="2">₹ {{ number_format($item->wholesale_price, 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Hospital Price</td>
                                            <td colspan="2">₹ {{ number_format($item->hospital_price, 2) }}</td>
                                        </tr>

                                        <tr style="background-color: #ffe0e0; font-weight: bold;">
                                            <td colspan="3">Sale</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Customer Type</td>
                                            <td colspan="2">{{ $item->customer_type }}</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Provided No of Cartons</td>
                                            <td colspan="2">{{ $item->sold_cartons }}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;&nbsp;&nbsp;Sell Price</td>
                                            <td colspan="2">₹ {{ number_format($item->price, 2) }}</td>
                                        </tr>
                                        <tr style="background-color: #ada7b9; font-weight: bold;">
                                            <td>&nbsp;&nbsp;&nbsp;Remaining Number of cartoons</td>
                                            <td colspan="2">{{ $item->batch_quantity }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

    @include('partials.right-sidebar')
    @include('partials.vendor-scripts')
    @include('partials.script')
    </body>

    </html>
