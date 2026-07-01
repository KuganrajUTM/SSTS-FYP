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
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .back-btn {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2d4a 100%);
        color: #fff;
        font-size: 0.9rem;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 600;
        border: none;
        transition: opacity 0.2s;
    }

    .back-btn:hover { opacity: 0.85; color: #fff; }

    .container-box {
        background: var(--white);
        padding: 30px;
        border: 1.5px solid var(--border);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,184,148,0.07);
    }

    .form-label {
        font-weight: 600;
        color: var(--navy);
        font-size: 1rem;
    }

    .highlight {
        font-weight: 700;
        color: var(--navy);
    }

    .input-field {
        margin-left: 10px;
        padding: 10px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        width: 150px;
        transition: border-color 0.2s;
    }

    .input-field:focus {
        outline: none;
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    .info-row { margin-bottom: 20px; }

    .issue-payment-btn {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: #fff;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 1rem;
        border: none;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
        transition: transform 0.2s, opacity 0.2s;
    }

    .issue-payment-btn:hover {
        transform: translateY(-2px);
        opacity: 0.92;
    }

    @media (max-width: 768px) {
        .input-field { font-size: 0.8rem; }
        .issue-payment-btn { font-size: 0.9rem; padding: 10px 20px; }
    }
</style>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">Issue Payment</div>
        <button class="btn back-btn" onclick="history.back()">Back</button>
    </div>

    <div class="container-box mt-4">
        <form method="POST" action="{{ route('pay-store') }}">
            @csrf

            <input type="hidden" name="child_id" value="{{ $customer->id }}">
            <input type="hidden" name="parent_id" value="{{ $customer->parent->id }}">

            <div class="info-row">
                <div class="row">
                    <div class="col-md-8">
                        <p><span class="form-label">Parent Name :</span> <span class="highlight">{{ $customer->parent->user->name }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p><span class="form-label">Child Name :</span> <span class="highlight">{{ $customer->name }}</span></p>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="row">
                    <div class="col-md-8">
                        <p><span class="form-label">Pickup :</span> <span class="highlight">{{ $customer->parent->location }}</span></p>
                    </div>
                    <div class="col-md-4">
                        <p><span class="form-label">Drop-Off :</span> <span class="highlight">{{ $customer->school_name }}</span></p>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="row align-items-center">
                    <div class="col-md-8 d-flex align-items-center">
                        <label for="amount" class="form-label">Enter Amount (RM) <span class="text-danger">*</span>:</label>
                        <input type="number" id="amount" name="amount" class="form-control input-field" placeholder="0.00" aria-label="Amount" required>
                    </div>
                    <div class="col-md-4">
                        <p><span class="form-label">Issue-date :</span> <span class="highlight">{{ Carbon\Carbon::now()->format('d-m-Y') }}</span></p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="issue-payment-btn" id="submit">
                    <i class="bi bi-wallet2"></i> Issue Payment
                </button>
            </div>
        </form>
    </div>
</div>


@endsection
