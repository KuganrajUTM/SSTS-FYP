@extends('layout.main-template')

@section('content')

<style>
  body {
    font-family: 'Roboto', sans-serif;
    background-color: #f3f4f6;
    color: #333;
  }

  .header-bar {
    background-color: black;
    height: 6px;
    width: 100%;
    margin-top: -1.5rem;
    border-radius: 3px;
  }

  .container-box {
    background: linear-gradient(135deg, #ffffff, #f9fafb);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
  }

  .content-box {
    background: #ffffff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
  }

  .back-btn {
    background-color: #0d6efd;
    color: #fff;
    font-size: 0.9rem;
    border-radius: 5px;
    padding: 8px 16px;
    font-weight: bold;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .back-btn:hover {
    background-color: #0b5ed7;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .issue-payment-btn {
    background: #087cfc;
    color: white;
    font-weight: bold;
    padding: 12px 25px;
    border-radius: 6px;
    font-size: 1rem;
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .issue-payment-btn:hover {
    background: #087cfc;
    transform: scale(1.03);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }

  .title-text {
    font-weight: bold;
    font-size: 2rem;
    color: black;
    margin-bottom: 10px;
  }

  .form-label {
    font-weight: bold;
    color: #555;
    font-size: 1rem;
  }

  .input-field {
    margin-left: 10px;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 0.9rem;
    width: 150px;
  }

  .highlight {
    font-weight: bold;
    color: #333;
  }

  .info-row {
    margin-bottom: 20px;
  }

  .text-center {
    margin-top: 30px;
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .content-box {
      padding: 20px;
    }

    .info-row .form-label {
      font-size: 0.8rem;
    }

    .input-field {
      font-size: 0.8rem;
    }

    .issue-payment-btn {
      font-size: 0.9rem;
      padding: 10px 20px;
    }
  }
</style>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="title-text">Edit Payment</div>
    <button class="btn back-btn" onclick="history.back()">Back</button>
  </div>

  <div class="container-box mt-4">
    <div class="content-box">
      <form method="POST" action="{{ route('pay-store') }}">
        @csrf 

        <input type="hidden" name="child_id" value="{{ $payment->child->id }}">
        <input type="hidden" name="parent_id" value="{{ $payment->parent->id }}">

        <div class="info-row">
          <div class="row">
            <div class="col-md-8">
              <p><span class="form-label">Parent Name :</span> <span class="highlight">{{ $payment->parent->user->name }}</span></p>
            </div>
            <div class="col-md-4">
              <p><span class="form-label">Child Name :</span> <span class="highlight">{{ $payment->child->name }}</span></p>
            </div>
          </div>
        </div>

        <div class="info-row">
          <div class="row">
            <div class="col-md-8">
              <p><span class="form-label">Pickup :</span> <span class="highlight">{{ $payment->parent->location }}</span></p>
            </div>
            <div class="col-md-4">
              <p><span class="form-label">Drop-Off :</span> <span class="highlight">{{ $payment->child->school_name }}</span></p>
            </div>
          </div>
        </div>

        <div class="info-row">
          <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center">
              <label for="amount" class="form-label">Enter Amount (RM) <span class="text-danger">*</span>:</label>
              <input type="number" id="amount" name="amount" class="form-control input-field" value={{ number_format($payment->pay_amount,2) }} aria-label="Amount" required>
            </div>
            <div class="col-md-4">
              <p><span class="form-label">Issue-date :</span> <span class="highlight">{{ \Carbon\Carbon::parse($payment->issue_date)->format('d-m-Y') }}</span></p>
            </div>
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn-warning issue-payment-btn" id="submit">
            <i class="bi bi-wallet2"></i> Edit Payment
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
