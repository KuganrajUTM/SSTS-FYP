@extends('layout.main-template')

@section('content')

  <style>
    .payment-container {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      background-color: #f9f9f9;
    }
    .payment-header {
      font-weight: bold;
      font-size: 1.2rem;
      text-align: center;
      padding-bottom: 15px;
      color: #333;
    }
    .payment-methods {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .payment-method {
      flex: 1;
      text-align: center;
      padding: 10px;
      cursor: pointer;
      border: 1px solid #ddd;
      border-radius: 8px;
  }
  .payment-form h5 {
      margin-bottom: 1.5rem;
  }
  .form-control:focus {
      box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
  }
  /* Hide the dropdown icon for the country select */
  select[disabled] {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: none;
      padding-right: 0; /* Remove extra padding if needed */
  }
  /* Additional styling to make the buttons appear side by side */
  .button-container {
      display: flex;
      gap: 1rem;
  }
  .button-container .btn {
      flex: 1;
  }
</style>

<div class="container my-5">
  <div class="payment-form bg-white p-4">
    <h5 class="text-center text-decoration-underline">Payment Confirmation</h5>
    
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

        <div class="mb-3">
            <label for="country" class="form-label">Country or Region</label>
            <select class="form-select" id="country" disabled>
                <option selected>Malaysia</option>
            </select>
        </div>

        <!-- Button container for Pay Now and Cancel buttons -->
        <div class="button-container">
            <!-- Pay Now Button (Green) -->
            <button type="submit" class="btn btn-success">Pay Now</button>

            <!-- Cancel Button (Red) -->
            <a href="javascript:history.back()" class="btn btn-danger">Cancel</a>
        </div>
    </form>
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
