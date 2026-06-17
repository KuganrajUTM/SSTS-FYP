@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --white:      #ffffff;
      --border:     rgba(0,184,148,0.25);
    }

    .back-btn {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2d4a 100%);
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        padding: 8px 20px;
        transition: opacity 0.2s;
    }

    .back-btn:hover { opacity: 0.85; color: #fff; }

    .header-bar {
        height: 5px;
        background: linear-gradient(90deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border-radius: 4px 4px 0 0;
    }

    .payment-card {
        border: 1.5px solid var(--border);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,184,148,0.07);
        background: var(--white);
        margin-top: 0;
        padding: 25px;
    }

    .payment-card h5 {
        color: var(--navy);
        font-weight: 700;
    }

    .btn-make-payment {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
        transition: transform 0.2s;
        text-decoration: none;
    }

    .btn-make-payment:hover {
        transform: translateY(-1px);
        color: #fff;
        opacity: 0.9;
    }

    .btn-print-invoice {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2d4a 100%);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        transition: opacity 0.2s;
        text-decoration: none;
    }

    .btn-print-invoice:hover {
        opacity: 0.85;
        color: #fff;
    }

    .btn-make-payment i, .btn-print-invoice i { margin-right: 15px; }

    .btn-proof {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: #fff; border: none;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; padding: 8px 16px; font-weight: 600;
        transition: opacity 0.2s; text-decoration: none;
    }
    .btn-proof:hover { opacity: 0.85; color: #fff; }
    .btn-proof i { margin-right: 15px; }
</style>

<div class="container mt-4">
    <div class="text-right mb-3">
        <a href="{{ url()->previous() }}" class="btn back-btn">Back</a>
    </div>

    <div class="header-bar"></div>

    <div class="payment-card">
        <div class="row">
            <div class="col-md-4">
                <h5><strong>Driver Name:</strong></h5>
            </div>
            <div class="col-md-4 text-right">
                <p>{{ $payment->driver->user->name }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h5><strong>Issue Date:</strong></h5>
            </div>
            <div class="col-md-4 text-right">
                <p>{{ $payment->issue_date->format('d-m-Y') }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h5><strong>Child Name:</strong></h5>
            </div>
            <div class="col-md-4 text-right">
                <p>{{ $payment->child->name }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h5><strong>Amount (RM):</strong></h5>
            </div>
            <div class="col-md-4 text-right">
                <p>{{ number_format($payment->pay_amount,2) }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <h5><strong>Status:</strong></h5>
            </div>
            <div class="col-md-4 text-right">
                @if ($payment->pay_status == 'Pending')
                    <span class="badge bg-warning text-white badge-lg">Pending</span>
                @elseif ($payment->pay_status == 'Overdue')
                    <span class="badge bg-danger text-white badge-lg">Overdue</span>
                @else
                    <span class="badge bg-success text-white badge-lg">Paid</span>
                @endif
            </div>
        </div>

        @if ($payment->pay_status == 'Pending' || $payment->pay_status == 'Overdue')
            <div class="row mt-4">
                <div class="col-md-2 text-center">
                    <a href="{{ route('payment.checkout', $payment->id) }}" class="btn-make-payment btn-custom">
                        <i class="fas fa-money-bill-wave"></i> <span style="padding-left: 15px;">Make Payment</span>
                    </a>
                </div>
                <div class="col-md-2 text-center">
                    <a href="{{ route('inv', $payment->id) }}" class="btn-print-invoice btn-custom">
                        <i class="fas fa-print"></i> <span style="padding-left: 15px;">Print Invoice</span>
                    </a>
                </div>
            </div>
        @else
            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('inv', $payment->id) }}" class="btn-print-invoice btn-custom">
                    <i class="fas fa-print"></i> <span style="padding-left: 15px;">Print Invoice</span>
                </a>
                @if(isset($receipt) && $receipt && $receipt->proof_path)
                    <a href="{{ asset('storage/' . $receipt->proof_path) }}" target="_blank" download class="btn-proof">
                        <i class="fas fa-image"></i> <span style="padding-left: 15px;">View Payment Proof</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
