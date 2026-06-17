<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #e6f9f5;
            color: #0a1628;
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
            border: 2px solid rgba(0,184,148,0.3);
            border-radius: 12px;
            padding: 24px;
            width: 600px;
            box-shadow: 0 8px 24px rgba(0,184,148,0.12);
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
            background-color: #e6f9f5;
            position: absolute;
            top: 20px;
            right: 20px;
            border-radius: 8px;
        }

        h1 {
            font-size: 24px;
            color: #0a1628;
            margin-bottom: 6px;
        }

        p { font-size: 14px; line-height: 1.5; margin-bottom: 5px; }

        .receipt-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
            margin-top: 20px;
        }

        .receipt-details div { display: flex; flex-direction: column; }

        .receipt-details strong {
            font-weight: bold;
            margin-bottom: 5px;
            color: #007a63;
        }

        .receipt-footer {
            text-align: center;
            font-size: 14px;
            color: #4a5568;
        }

        .receipt-footer a { color: #00b894; text-decoration: none; }
        .receipt-footer a:hover { text-decoration: underline; }

        button {
            background: linear-gradient(135deg, #00b894 0%, #007a63 100%);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: 600;
        }

        button:hover { opacity: 0.9; }

        hr {
            border: none;
            border-top: 3px solid #00b894;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="">
        <header class="receipt-header">
            <div>
                <h1>Prameswary Transportation Entreprise</h1>
                <p>No.262, Jalan Springhill, 10/2, Bandar SPringhill, 71010, 
                    Lukut, Port Dikscon, Negeri Sembilan</p>
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
