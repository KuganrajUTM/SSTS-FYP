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

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
        text-align: center;
        text-decoration: underline;
    }

    .current-month {
        text-align: center;
        color: var(--emerald-dk);
        font-weight: 500;
        font-size: 1.5rem;
        margin-bottom: 30px;
    }

    .info-card {
        border: 1.5px solid var(--border);
        border-radius: 15px;
        padding: 20px;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0,184,148,0.08);
    }

    .info-card:hover {
        box-shadow: 0 8px 24px rgba(0,184,148,0.18);
        transform: translateY(-3px);
    }

    .info-card-title {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        font-size: 1.1em;
        margin: 0;
    }

    .underline-name { text-decoration: underline; }

    .info-card-text { font-weight: 400; }

    .payment-issued {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: #fff;
    }

    .payment-issued .info-card-title,
    .payment-issued .info-card-text { color: #fff; }

    .no-payment {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2d4a 100%);
        color: #fff;
    }

    .no-payment .info-card-title,
    .no-payment .info-card-text { color: #e6f9f5; }
</style>

<div class="container my-5">
    <h1 class="page-title">Issue Payment</h1>
    <p class="current-month">{{ \Carbon\Carbon::now()->format('F Y') }}</p>

    <div class="row g-4">
        @foreach ($customers as $cust)
            @php
                $found = false;
                $paid = false;
            @endphp
        @foreach($payments as $pay)
            @if ($pay->child_id === $cust->id)
                @php
                    $found = true;
                    if($pay->pay_status != 'Paid')
                        $paid = false;
                    break;
                @endphp
            @endif
        @endforeach

            @if($found === true && $paid === false)
                <div class="col-md-4">
                    <div class="info-card payment-issued" onclick="window.location.href='{{ route('driver-edit', ['id'=>$cust->id] )}}'">
                        <p class="info-card-title text-center"><span class="underline-name">{{ $cust->parent->user->name }}</span></p>
                        <p class="info-card-title mt-2">Child: <span class="info-card-text">{{ $cust->name }}</span></p>
                        <p class="info-card-title">Pickup: <span class="info-card-text">{{ $cust->parent->location }}</span></p>
                        <p class="info-card-title">Drop-Off: <span class="info-card-text">{{ $cust->school_name }}</span></p>
                        <p class="info-card-title">Payment: <span class="info-card-text">Issued</span></p>
                    </div>
                </div>
            @elseif($paid === false)
                <div class="col-md-4">
                    <div class="info-card no-payment" onclick="window.location.href='{{ route('driver-app', ['id'=>$cust->id] )}}'">
                        <p class="info-card-title text-center"><span class="underline-name">{{ $cust->parent->user->name }}</span></p>
                        <p class="info-card-title mt-2">Child: <span class="info-card-text">{{ $cust->name }}</span></p>
                        <p class="info-card-title">Pickup: <span class="info-card-text">Perak</span></p>
                        <p class="info-card-title">Drop-Off: <span class="info-card-text">Selangor</span></p>
                        <p class="info-card-title">Payment: <span class="info-card-text">Pending Issued</span></p>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
