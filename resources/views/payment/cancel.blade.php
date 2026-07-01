@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
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

    .fail-icon {
        font-size: 5rem;
        color: #e74c3c;
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
        background: #e74c3c;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(231,76,60,0.2);
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .dashboard-btn:hover { background-color: #c0392b; }
</style>

<div class="overlay-box text-center">
    <div class="fail-icon">
        <i class="fas fa-times-circle"></i>
    </div>
    <div class="message">Payment Failed!</div>
    <p class="text-muted">There was an issue with processing your payment. Please try again later.</p>
    <button class="dashboard-btn" onclick="window.location.href='{{ route('parent_pay') }}'">Go to Dashboard</button>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

@endsection
