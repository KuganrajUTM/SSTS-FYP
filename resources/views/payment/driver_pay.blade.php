@extends('layout.main-template')

@section('content')

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f5f5f5;
    }
    .header-title {
        text-align: center;
        color: #1e3799;
        font-weight: 700;
        font-size: 2rem;
        margin-bottom: 20px;
    }
    .current-month {
        text-align: center;
        color: #4a69bd;
        font-weight: 500;
        font-size: 1.5rem;
        margin-bottom: 30px;
    }
    .info-card {
        background: linear-gradient(135deg, #4a69bd 0%, #1e3799 100%);
        border: none;
        padding: 20px;
        border-radius: 12px;
        text-align: left;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #e9f0f7;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .info-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        transform: scale(1.03);
    }
    .info-card-title {
        font-weight: 700;
        font-size: 1.2em;
        color: #ffffff;
        margin: 0;
    }
    .underline-name {
        text-decoration: underline;
    }
    .info-card-text {
        font-weight: 400;
        color: #dfe6ed;
    }
    .payment-issued {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    }
    .no-payment {
        background: linear-gradient(135deg, #4a69bd 0%, #1e3799 100%);
    }
</style>

<div class="container my-5">
    <!-- Title Section -->
    <h1 class="header-title text-decoration-underline">Issue Payment</h1>
    <!-- Current Month -->
    <p class="current-month">{{ \Carbon\Carbon::now()->format('F Y') }}</p>

    <div class="row g-4">
        <!-- Example Card -->
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
