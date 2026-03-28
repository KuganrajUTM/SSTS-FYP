<!-- resources/views/dashboard.blade.php -->
@extends('layout.main-template')

@section('content')

        <style>
            table#datatablesSimple th:nth-child(1), table#datatablesSimple td:nth-child(1) {
                width: 5%;
            }
            table#datatablesSimple th:nth-child(2), table#datatablesSimple td:nth-child(2) {
                width: 20%;
            }
            table#datatablesSimple th:nth-child(3), table#datatablesSimple td:nth-child(3) {
                width: 20%;
            }
            table#datatablesSimple th:nth-child(4), table#datatablesSimple td:nth-child(4) {
                width: 15%;
            }
            table#datatablesSimple th:nth-child(5), table#datatablesSimple td:nth-child(5) {
                width: 15%;
            }
            table#datatablesSimple th:nth-child(7), table#datatablesSimple td:nth-child(7) {

                text-align: center;
            }
        </style>
    <h1 class="mt-4">Payment</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Payment</li>
    </ol>
    <div class="row">
    <div class="col-xl-3 col-md-6 ">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">Pending Payment</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" style="text-decoration:none;">{{ $pay_count->pending_count }}</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">Successful Payment</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" style="text-decoration:none;">{{ $pay_count->paid_count }}</a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">Overdue Payment</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" style="text-decoration:none;">{{ $pay_count->overdue_count }}</a>
            </div>
        </div>
    </div>
    </div>

        <div class="d-flex justify-content-end mb-3">
            <form method="GET" action="{{ route('parent_pay') }}" class="d-flex align-items-center gap-2" style="width: auto;">
                <!-- Payment Status Dropdown -->
                <label for="status" class="mb-0">Payment Status:</label>
                <select name="status" class="form-select form-select-sm" id="status" onchange="this.form.submit()" style="width: auto;">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Overdue" {{ request('status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
        
                <label for="month" class="mb-0">Month:</label>
                <select name="month" class="form-select form-select-sm" id="month" onchange="this.form.submit()" size="1" style="width: auto; max-height: 120px; overflow-y: auto;">
                    <option value="" {{ request('month') == '' ? 'selected' : '' }}>All Months</option>
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            
                <!-- Year Dropdown -->
                <label for="year" class="mb-0">Year:</label>
                <select name="year" class="form-select form-select-sm" id="year" onchange="this.form.submit()" style="width: auto;">
                    <option value="" {{ request('year') == '' ? 'selected' : '' }}>All Years</option>
                    @foreach (range(now()->year, now()->year - 5) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Payment List

            </div>
            @if ($userRole === 'P')
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Child</th>
                                <th>Driver</th>
                                <th>Amount (RM)</th>
                                <th>Date</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Child</th>
                                <th>Driver</th>
                                <th>Amount (RM)</th>
                                <th>Date</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            
                                @foreach ($payment as $index => $pay)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pay->child->name }}</td>
                                    <td>{{ $pay->driver->user->name }}</td>
                                    <td>{{ $pay->pay_amount }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pay->issue_date)->format('d-m-Y') }}</td>
                                    @if ($pay->pay_status == 'Overdue')
                                        <td><span class="badge bg-danger text-white badge-lg">Overdue</span></td>
                                        <td>
                                            <a href="{{ route('payment.checkout', $pay->id) }}" class="btn btn-sm btn-success delete-btn">
                                                <span><i class="fa-solid fa-sack-dollar"></i></span>
                                            </a>
                                            <a href="{{ route('view_pay', $pay->id) }}" class="btn btn-sm btn-primary view-btn">
                                                <span><i class="fa-solid fa-eye"></i></span>
                                            </a>
                                        </td>
                                    @elseif ($pay->pay_status == 'Pending')
                                        <td><span class="badge bg-warning text-white badge-lg">Pending</span></td>
                                        <td>
                                            <a href="{{ route('payment.checkout', $pay->id) }}" class="btn btn-sm btn-success delete-btn">
                                                <span><i class="fa-solid fa-sack-dollar"></i></span>
                                            </a>
                                            <a href="{{ route('view_pay', $pay->id) }}" class="btn btn-sm btn-primary view-btn">
                                                <span><i class="fa-solid fa-eye"></i></span>
                                            </a>
                                        </td>
                                    @else
                                        <td><span class="badge bg-success text-white badge-lg">Paid</span></td>
                                        <td>
                                            <a href="{{ route('view_pay', $pay->id) }}" class="btn btn-sm btn-primary view-btn">
                                                <span><i class="fa-solid fa-eye"></i></span>
                                            </a>
                                        </td>         
                                    @endif
                                </tr>

                                @endforeach

            

                        </tbody>
                    </table>
                    
                    </div>
                @elseif ($userRole === 'D')
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Child</th>
                                <th>Parent</th>
                                <th>Amount (RM)</th>
                                <th>Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Child</th>
                                <th>Parent</th>
                                <th>Amount (RM)</th>
                                <th>Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            
                                @foreach ($payment as $index => $pay)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pay->child->name }}</td>
                                    <td>{{ $pay->parent->user->name }}</td>
                                    <td>{{ $pay->pay_amount }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pay->issue_date)->format('d-m-Y') }}</td>
                                    <td>
                                        @if($pay->pay_status == 'Paid')
                                            <span class="badge bg-success text-white badge-lg">Paid</span>
                                        @else

                                        <select name="pay_status" id="pay_status_{{ $pay->id }}" class="form-select form-select-sm" onchange="openConfirmationModal({{ $pay->id }}, this.value)">
                                            @if($pay->pay_status == 'Pending' || $pay->pay_status == 'Overdue')
                                                <option value="{{ $pay->pay_status }}" selected>{{ $pay->pay_status }}</option>
                                                <option value="Paid">Paid</option>
                                            @endif
                                        </select>
                                        @endif
                                    </td>
                                </tr>

                                @endforeach

                        </tbody>
                    </table>
                    
                    </div>

                    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Payment Status Update</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to change the payment status to <strong id="newStatus"></strong>?
                                </div>
                                <div class="modal-footer">
                                    <form id="statusUpdateForm" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Hidden inputs -->
                                        <input type="hidden" id="modalPayId" name="pay_id">
                                        <input type="hidden" id="modalPayStatus" name="pay_status">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>                         
                    
        
                @endif
        </div>

        <script>
            function openConfirmationModal(paymentId, newStatus) {
                // Update modal content
                document.getElementById('newStatus').innerText = newStatus;
                document.getElementById('modalPayStatus').value = newStatus;
                document.getElementById('modalPayId').value = paymentId; // Set pay_id in the hidden input

                // Update the form action URL dynamically
                const formActionUrl = `/payment/status/${paymentId}`;
                document.getElementById('statusUpdateForm').setAttribute('action', formActionUrl);

                // Show the modal
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            }

        </script>
    
@endsection
