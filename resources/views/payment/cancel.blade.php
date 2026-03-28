
.dashboard-btn {
    background-color: #dc3545;  /* Changed from green to red */
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
  }

  .dashboard-btn:hover {
    background-color: #c82333;  /* Darker red on hover */
  }
</style>

<div class="overlay-box text-center">
  <div class="success-icon">
    <i class="fas fa-times-circle"></i>  <!-- Changed from check-circle to times-circle -->
  </div>
  <div class="message">Payment Failed!</div>  <!-- Changed message to reflect failure -->
  <p class="text-muted">There was an issue with processing your payment. Please try again later.</p>
  <button class="dashboard-btn" onclick="window.location.href='{{ route('parent_pay') }}'">Go to Dashboard</button>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection