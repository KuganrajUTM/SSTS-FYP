<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTransit</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 30px;
            border: 1px solid #e1e4e8;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .header-logo img {
            max-width: 150px;
        }
        .invoice-title {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            text-transform: uppercase;
            margin: 20px 0;
            text-align: center;
        }
        .header-bar {
            height: 4px;
            background-color: #007bff;
            margin: 20px 0;
            border-radius: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #e1e4e8;
            padding: 10px;
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: #ffffff;
            font- weight: 600;
        }
        .table tfoot th {
            background-color: #f1f1f1;
            color: #00 ```html
        }
        .note {
            font-size: 0.85em;
            color: #6c757d;
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.8em;
            color: #6c757d;
        }
        .highlight {
            background-color: #e7f1ff;
            border-left: 5px solid #007bff;
            padding: 10px;
            margin-bottom: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        .col {
            flex: 1;
            padding: 0 15px;
        }
        .col-md-6 {
            flex: 0 0 50%;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .combined-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .driver-name {
            font-size: 1.5em; /* Increased font size for driver name */
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Header Section -->
        <div class="header">
            <div class="header-logo">
                <img src="assets/img/photo_2024-10-22_11-35-22-Photoroom.png" alt="Edutransit Logo">
            </div>
            <h1 class="invoice-title">Invoice</h1>
        </div>
        
        <hr>
    
        <!-- Driver Name -->
        <div class="row text-center">
            <div class="col">
                <h5 class="driver-name">Driver: {{ $payment->driver->user->name }}</h5> <!-- Increased size -->
            </div>
        </div>
    
        <hr>
    
        <!-- Company and Invoice Details -->
        <div class="combined-details">
            <div class="col col-md-6">
                <h4 class="font-weight-bold">EduTransit</h4>
                <p>24-1, Jalan Limau Kecil<br>
                    Taman Durian Besar,<br>
                    20000 Rambutan, Kelantan.</p>
                <p><strong>Phone:</strong> +60123456789</p>
                <p><strong>Email:</strong> agentp@gmail.com</p>
            </div>
            <div class="col col-md-6 text-left"> <!-- Changed to text-left -->
                <p><strong>Child Name:</strong> {{ $payment->child->name }}</p>
                <p><strong>Invoice Date:</strong> {{ now()->format('d-m-Y') }}</p>
                <p><strong>Payment Status:</strong> <span class="text-success font-weight-bold">Paid</span></p>
            </div>
        </div>
    
        <!-- Divider -->
        <div class="header-bar"></div>
    
        <!-- Invoice Table -->
        <div class="row">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Amount (RM)</th>
                                <th>Total (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Invoice - {{ now()->format('F Y') }}</td>
                                <td>1</td>
                                <td>{{ $payment->pay_amount }}</td>
                                <td>{{ $payment->pay_amount }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right" style="color:black"><strong>Total (RM):</strong></th>
                                <th style="color:black"><strong>{{ $payment->pay_amount }}</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    
        <!-- Note -->
        <div class="row">
            <div class="col text-center">
                <p class="note">NOTE: This is a computer-generated receipt and does not require a physical signature.</p>
            </div>
        </div>

    </div>
</body>
</html>