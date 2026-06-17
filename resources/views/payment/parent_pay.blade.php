@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --border:     rgba(0,184,148,0.25);
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
    }

    .payment-card {
        max-width: 520px;
        margin: 0 auto;
        border: 1.5px solid var(--border);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,184,148,0.08);
        background: var(--white);
    }

    .payment-card-header {
        background-color: var(--emerald-lt);
        border-bottom: 1.5px solid var(--border);
        color: var(--emerald-dk);
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        padding: 1rem 1.25rem;
        text-align: center;
        font-size: 1.1rem;
    }

    .payment-card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--navy);
    }

    .form-control, .form-select {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    .btn-pay {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none;
        color: #fff;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
        transition: transform 0.2s;
        flex: 1;
    }

    .btn-pay:hover {
        background: var(--emerald-dk);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #e74c3c;
        border: none;
        color: #fff;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        flex: 1;
        text-align: center;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #c0392b;
        color: #fff;
    }

    .divider {
        display: flex; align-items: center; gap: 1rem;
        color: #888; font-size: 0.9rem; margin: 1.5rem 0;
    }
    .divider::before, .divider::after {
        content: ''; flex: 1; height: 1px; background: var(--border);
    }

    .btn-qr {
        background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 100%);
        border: none; color: #fff;
        font-family: 'Syne', sans-serif; font-weight: 600;
        padding: 10px 20px; border-radius: 8px; width: 100%;
        box-shadow: 0 4px 15px rgba(10,22,40,0.2);
        transition: transform 0.2s; text-decoration: none;
        display: block; text-align: center;
    }
    .btn-qr:hover { opacity: 0.88; color: #fff; transform: translateY(-1px); }

    select[disabled] {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: none;
        padding-right: 0;
    }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-center mb-4">
        <h2 class="page-title">Payment Confirmation</h2>
    </div>

    <div class="payment-card">
        <div class="payment-card-header">
            <i class="fas fa-credit-card me-2"></i>Secure Card Payment
        </div>
        <div class="payment-card-body">
            <form id="checkout-payment" method="POST" action="{{ route('payment.process', ['id' => $payment->id]) }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="{{ $payment->parent->user->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="payAmount" class="form-label">Amount (RM)</label>
                    <input type="text" class="form-control" id="payAmount" value="RM {{ number_format($payment->pay_amount, 2) }}" readonly>
                </div>

                <div class="mb-4">
                    <label for="country" class="form-label">Country or Region</label>
                    <select class="form-select" id="country" disabled>
                        <option selected>Malaysia</option>
                    </select>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn-pay"><i class="fas fa-credit-card me-2"></i>Pay with Card</button>
                    <a href="javascript:history.back()" class="btn-cancel">Cancel</a>
                </div>
            </form>

            <div class="divider">or</div>

            <a href="{{ route('qr.pay', $payment->id) }}" class="btn-qr">
                <i class="fas fa-qrcode me-2"></i> Scan QR Pay
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
<script>
  function selectMethod(element) {
    document.querySelectorAll('.payment-method').forEach(function(method) {
      method.classList.remove('active');
    });
    element.classList.add('active');
  }
</script>

@endsection
