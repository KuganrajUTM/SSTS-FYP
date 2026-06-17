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

    .overlay-box {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--white);
        padding: 40px 30px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,184,148,0.12);
        z-index: 100;
        width: 100%;
        max-width: 520px;
    }

    .success-icon {
        font-size: 5rem;
        color: var(--emerald);
        margin-bottom: 20px;
    }

    .message {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 15px;
    }

    .text-muted { margin-bottom: 25px; }

    .dashboard-btn {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
        transition: transform 0.2s, opacity 0.2s;
        width: 100%;
    }

    .dashboard-btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
</style>

<div class="overlay-box text-center">
    <div class="success-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="message">Payment Succeeded!</div>
    <p class="text-muted">Thank you for processing your payment on {{ now()->format('d M Y') }}.</p>
    <button class="dashboard-btn" onclick="window.location.href='{{ route('parent_pay') }}'">Go to Dashboard</button>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
