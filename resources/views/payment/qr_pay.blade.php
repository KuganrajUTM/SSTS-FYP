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

    .qr-card {
        max-width: 520px;
        margin: 0 auto;
        border: 1.5px solid var(--border);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,184,148,0.08);
        background: var(--white);
    }

    .qr-card-header {
        background-color: var(--emerald-lt);
        border-bottom: 1.5px solid var(--border);
        color: var(--emerald-dk);
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        padding: 1rem 1.25rem;
        text-align: center;
        font-size: 1.1rem;
    }

    .qr-card-body { padding: 2rem; }

    .amount-badge {
        background: var(--emerald-lt);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 0.75rem 1.25rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .amount-badge .label { font-size: 0.85rem; color: var(--slate); font-weight: 600; }
    .amount-badge .value { font-size: 1.6rem; font-weight: 800; color: var(--navy); }

    .qr-image-wrap {
        text-align: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        border: 1.5px dashed var(--border);
        border-radius: 12px;
        background: #fafffe;
    }

    .qr-image-wrap img {
        max-width: 240px;
        width: 100%;
        border-radius: 8px;
    }

    .qr-note {
        font-size: 0.85rem;
        color: var(--slate);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .upload-label {
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 0.4rem;
        display: block;
    }

    .form-control {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    .btn-upload {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none; color: #fff;
        font-family: 'Syne', sans-serif; font-weight: 600;
        padding: 10px 20px; border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
        transition: transform 0.2s; flex: 1;
    }

    .btn-upload:hover { transform: translateY(-1px); color: #fff; }

    .btn-back-cancel {
        background: #6c757d;
        border: none; color: #fff;
        font-family: 'Syne', sans-serif; font-weight: 600;
        padding: 10px 20px; border-radius: 8px; flex: 1;
        text-align: center; text-decoration: none;
    }

    .btn-back-cancel:hover { background: #5a6268; color: #fff; }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
    }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-center mb-4">
        <h2 class="page-title">QR Pay</h2>
    </div>

    <div class="qr-card">
        <div class="qr-card-header">
            <i class="fas fa-qrcode me-2"></i> Scan & Pay
        </div>
        <div class="qr-card-body">

            <div class="amount-badge">
                <div class="label">Amount to Pay</div>
                <div class="value">RM {{ number_format($payment->pay_amount, 2) }}</div>
            </div>

            <div class="qr-image-wrap">
                <img src="{{ asset('assets/img/qr-pay.png') }}" alt="QR Code">
            </div>

            <p class="qr-note">
                Scan the QR code above using your banking app to complete the payment,
                then upload your payment screenshot as proof below.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('qr.upload', $payment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="upload-label">Upload Payment Proof</label>
                    <input type="file" name="proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    <small class="text-muted">Accepted: JPG, PNG, PDF — max 5 MB</small>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn-upload">
                        <i class="fas fa-upload me-2"></i> Submit Proof
                    </button>
                    <a href="javascript:history.back()" class="btn-back-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
