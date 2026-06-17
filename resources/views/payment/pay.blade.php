<!-- resources/views/dashboard.blade.php -->
@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --border:     rgba(0,184,148,0.25);
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .stat-card:hover { transform: translateY(-3px); }

    .stat-card.pending  { background: linear-gradient(135deg, #f6a623 0%, #e08c00 100%); }
    .stat-card.paid     { background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%); }
    .stat-card.overdue  { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }

    .stat-card .card-body,
    .stat-card .card-footer {
        color: #fff;
    }

    .stat-card .card-footer {
        background: rgba(0,0,0,0.15);
        border-top: none;
        font-size: 1.4rem;
        font-weight: 700;
        font-family: 'Syne', sans-serif;
    }

    .card {
        border: 1.5px solid var(--border);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,184,148,0.05);
        background: var(--white);
        margin-bottom: 2rem;
    }

    .card-header {
        background-color: var(--emerald-lt) !important;
        border-bottom: 1.5px solid var(--border);
        color: var(--emerald-dk);
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        padding: 1rem 1.25rem;
    }

    .form-select, .form-select-sm {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-select:focus, .form-select-sm:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    table#datatablesSimple th:nth-child(1), table#datatablesSimple td:nth-child(1) { width: 5%; }
    table#datatablesSimple th:nth-child(2), table#datatablesSimple td:nth-child(2) { width: 20%; }
    table#datatablesSimple th:nth-child(3), table#datatablesSimple td:nth-child(3) { width: 20%; }
    table#datatablesSimple th:nth-child(4), table#datatablesSimple td:nth-child(4) { width: 15%; }
    table#datatablesSimple th:nth-child(5), table#datatablesSimple td:nth-child(5) { width: 15%; }
    table#datatablesSimple th:nth-child(7), table#datatablesSimple td:nth-child(7) { text-align: center; }

    .modal-header {
        background-color: var(--emerald-lt);
        border-bottom: 1.5px solid var(--border);
        color: var(--emerald-dk);
        font-family: 'Syne', sans-serif;
        font-weight: 700;
    }

    .btn-confirm {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none;
        color: #fff;
        font-weight: 600;
    }

    .btn-confirm:hover {
        background: var(--emerald-dk);
        color: #fff;
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">Payment</h2>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card pending">
                <div class="card-body">Pending Payment</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ $pay_count->pending_count }}</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card paid">
                <div class="card-body">Successful Payment</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ $pay_count->paid_count }}</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card overdue">
                <div class="card-body">Overdue Payment</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span>{{ $pay_count->overdue_count }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <form method="GET" action="{{ route('parent_pay') }}" class="d-flex align-items-center gap-2" style="width: auto;">
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
            <i class="fas fa-table me-1"></i> Payment List
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

        @endif
    </div>
</div>

@endsection
