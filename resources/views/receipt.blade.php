<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        /* Your CSS styling for the receipt */
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .receipt-header {
            text-align: center;
            font-size: 24px;
        }
        .receipt-details {
            margin-top: 20px;
        }
        .receipt-details p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>Receipt</h2>
        </div>
        <div class="receipt-details">
            <p><strong>Customer Name:</strong> {{ $customerName }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Amount:</strong> ${{ number_format($amount, 2) }}</p>
            <p><strong>Receipt Number:</strong> {{ $receiptNumber }}</p>
        </div>
    </div>
</body>
</html>
