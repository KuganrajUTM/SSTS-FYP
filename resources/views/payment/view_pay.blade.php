@extends('layout.main-template')

@section('content')

        <style>
            .payment-card {
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-top: 20px;
            }
            .header-bar {
                height: 6px;
                background-color: #ffa500;
                border-radius: 4px 4px 0 0;
            }
            .back-btn {
                margin-top: -8px;
                margin-bottom: 16px;
            }
            .status-badge {
                padding: 0.4em 0.8em;
                border-radius: 20px;
                font-size: 0.9em;
                font-weight: bold;
            }
            .status-pending {
                background-color: #ffc107;
                color: #fff;
            }
            .btn-custom {
                width: auto;
                padding: 8px 16px;
                font-size: 0.9em;
            }
            .btn-make-payment {
                background-color: #28a745;
                color: #fff;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .btn-make-payment:hover {
                background-color: #218838;
            }
            .btn-print-invoice {
                background-color: #17a2b8;
                color: #fff;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .btn-print-invoice:hover {
                background-color: #138496;
            }
            .btn-make-payment i, .btn-print-invoice i {
                margin-right: 15px;
            }
        </style>

    <div class="container mt-4">
        <!-- Back button -->
        <div class="text-right">
            <a href="{{ url()->previous() }}" class="btn btn-primary back-btn">Back</a>
        </div>
        
        <!-- Header bar -->
        <div class="header-bar"></div>
        
        <!-- Payment Detail Card -->
        <div class="payment-card">
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>Driver Name:</strong></h5>
                </div>
                <div class="col-md-4 text-right">
                    <p>{{ $payment->driver->user->name }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>Issue Date:</strong></h5>
                </div>
                <div class="col-md-4 text-right">
                    <p>{{ $payment->issue_date->format('d-m-Y') }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>Child Name:</strong></h5>
                </div>
                <div class="col-md-4 text-right">
                    <p>{{ $payment->child->name }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>Amount (RM):</strong></h5>
                </div>
                <div class="col-md-4 text-right">
                    <p>{{ number_format($payment->pay_amount,2) }}</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h5><strong>Status:</strong></h5>
                </div>
                <div class="col-md-4 text-right">
                    @if ($payment->pay_status == 'Pending')
                        <span class="badge bg-warning text-white badge-lg">Pending</span>
                        
                    @elseif ($payment->pay_status == 'Overdue')
                        <span class="badge bg-danger text-white badge-lg">Overdue</span>
                    @else
                        <span class="badge bg-success text-white badge-lg">Paid</span>
                    @endif

                </div>
            </div>
            
            @if ($payment->pay_status == 'Pending' || $payment->pay_status == 'Overdue')
                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-md-2 text-center">
                        <a href="{{ route('payment.checkout', $payment->id) }}" class="btn btn-make-payment btn-custom">
                            <i class="fas fa-money-bill-wave"></i> <span style="padding-left: 15px;">Make Payment</span>
                        </a>
                    </div>
                    <div class="col-md-2 text-center">
                        <a href="{{ route('inv', $payment->id) }}" class="btn btn-print-invoice btn-custom">
                            <i class="fas fa-print"></i> <span style="padding-left: 15px;">Print Invoice</span>
                        </a>
                    </div>
                </div>
            @else
                <!-- Buttons -->
                <div class="col-md-2 text-center mt-4">
                    <a href="{{ route('inv', $payment->id) }}" class="btn btn-print-invoice btn-custom">
                        <i class="fas fa-print"></i> <span style="padding-left: 15px;">Print Invoice</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- jQuery and Bootstrap JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
@endsection
