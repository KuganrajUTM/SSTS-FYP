<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .receipt-container {
            display: grid;
            grid-template-rows: auto 1fr auto auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            gap: 20px;
            position: relative;
        }

        .receipt-header {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 10px;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #999;
            font-size: 12px;
            background-color: #f9f9f9;
            position: absolute;
            top: 20px;
            right: 20px;
            border-radius: 5px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 5px;
        }

        .receipt-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
            margin-top: 20px;
        }

        .receipt-details div {
            display: flex;
            flex-direction: column;
        }

        .receipt-details strong {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receipt-footer {
            text-align: center;
            font-size: 14px;
        }

        .receipt-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .receipt-footer a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        hr {
            border: none;
            border-top: 3px solid #007bff;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="">
        <header class="receipt-header">
            <div>
                <h1>Agent P Transportation Services</h1>
                <p>124-1, Jalan Limau Kecil, 20000 Rambutan, Kelantan</p>
                <p>Phone: +60357090 | Email: agentP@gmail.com</p>
            </div>
            <div class="logo-placeholder">
                <img src="assets/img/photo_2024-10-22_11-35-22-Photoroom.png" alt="SchoolBusPro Logo" style="width: 80px; height: 80px;">
            </div>
        </header>
        <hr>
        <main class="receipt-details">
            <div>
                <strong>Name:</strong>
                <span>{{ $receipt->parent->user->name }}</span>
            </div>
            <br>
            <div>
                <strong>Child Name:</strong>
                <span>{{ $receipt->child->name }}</span>
            </div>
            <br>
            <div>
                <strong>Driver Name:</strong>
                <span>{{ $receipt->child->driver->user->name }}</span>
            </div>
            <br>
            <div>
                <strong>Amount Paid:</strong>
                <span>{{ $receipt->rec_amount }}</span>
            </div>
            <br>
            <div>
                <strong>Date Paid:</strong>
                <span>{{ $receipt->rec_date->format('d-m-Y') }}</span>
            </div>
            <br>
            <div>
                <strong>Receipt Number:</strong>
                <span>{{ $receipt->rec_num }}</span>
            </div>
            <br>
            <div>
                <strong>Payment Method:</strong>
                <span>{{ $receipt->payment_method }}</span>
            </div>
        </main>
        <footer class="receipt-footer" style="margin-top:50px">
            <p>
                Thank you for your payment. If you have any questions, feel free to contact us at 
                <a href="mailto:contact@xyz.com">agentP@gmail.com</a>.
            </p>
        </footer>
    </div>
</body>
</html>
