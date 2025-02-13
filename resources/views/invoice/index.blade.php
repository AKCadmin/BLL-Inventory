<!-- resources/views/invoice/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-details, .customer-details {
            margin-bottom: 20px;
        }
        .invoice-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .batch-info {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Invoice #{{ $invoice_number }}</p>
    </div>

    <div class="invoice-info">
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Order ID:</strong> {{ $order_id }}</p>
    </div>

    <div class="company-details">
        <h3>Company Details:</h3>
        <p>{{ $company_name }}</p>
        <p>{{ $company_address }}</p>
        <p>Email: {{ $company_email }}</p>
        <p>Phone: {{ $company_phone }}</p>
    </div>

    <div class="customer-details">
        <h3>Customer Details:</h3>
        <p><strong>Name:</strong> {{ $products[0]['customer'] }}</p>
        <p><strong>Type:</strong> {{ $customer_type }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Packaging Type</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Cartoon Price</th>
                <th>Amount</th>
                <th>Batch Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['description'] }}</td>
                <td>{{ $product['packaging_type'] }}</td>
                <td>{{ $product['unit'] }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ number_format($product['unit_price'], 2) }}</td>
                <td>{{ number_format($product['amount'], 2) }}</td>
                <td class="batch-info">
                    Batch: {{ $product['batch']['batch_number'] }}<br>
                    Mfg: {{ $product['batch']['manufacturing_date'] }}<br>
                    Exp: {{ $product['batch']['expiry_date'] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <h3>Total Amount: {{ number_format($total_amount, 2) }}</h3>
    </div>
</body>
</html>