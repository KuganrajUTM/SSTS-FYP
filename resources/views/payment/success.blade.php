@extends('layout.main-template')

@section('content')

<style>
  .overlay-box {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 100;
    width: 100%;
    max-width: 600px;  /* Increased width */
  }

  .success-icon {
    font-size: 5rem;
    color: #28a745;
    margin-bottom: 20px;
  }

  .message {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 15px;
  }

  .text-muted {
    margin-bottom: 25px;
  }

  .dashboard-btn {
    background-color: #28a745;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
  }

  .dashboard-btn:hover {
    background-color: #218838;
  }
</style>

<div class="overlay-box text-center">
  <div class="success-icon">
    <i class="fas fa-check-circle"></i>
  </div>
  <div class="message">Payment Succeeded!</div>
  <p class="text-muted">Thank you for processing your payment on {{ now() }}. </p>
  <button class="dashboard-btn" onclick="window.location.href='{{ route('parent_pay') }}'">Go to Dashboard</button>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection