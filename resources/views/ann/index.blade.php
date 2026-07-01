@extends('layout.main-template')

@section('content')
<style>
    /* Theme Variables matching your reference */
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --bg:         #f5f7fa;
      --border:     rgba(0,184,148,0.25);
    }

    .ann-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
        margin-top: 2rem;
    }

    /* Customizing the Cards */
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

    /* Table Styling */
    .table thead {
        background-color: var(--navy);
        color: var(--white);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 184, 148, 0.02);
    }

    /* Button Customization */
    .btn-primary {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,184,148,0.2);
    }

    .btn-primary:hover {
        background: var(--emerald-dk);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background-color: var(--slate);
        border: none;
    }

    /* Filter inputs styling */
    .form-select-sm, .form-control-sm {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-select-sm:focus, .form-control-sm:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="ann-title">Announcements</h2>
        <a href="{{ route('ann.create') }}" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus-circle me-1"></i> Add Announcement
        </a>
    </div>

    @if($userRole == 'P')

    {{-- Near-Due Payment Alerts --}}
    @if($nearDuePayments->count() > 0)
    <div class="card mb-3" style="border-color:#f39c12;">
        <div class="card-header" style="background:#fff9e6 !important; border-bottom-color:#f39c12; color:#856404; font-family:'Syne',sans-serif; font-weight:700; padding:1rem 1.25rem;">
            <i class="fas fa-clock me-2"></i> Payment Due Soon
        </div>
        <div class="card-body pb-2">
            @foreach($nearDuePayments as $pay)
            <div class="d-flex align-items-start gap-3 p-3 mb-2" style="background:#fff9e6; border:1.5px solid rgba(243,156,18,0.4); border-radius:10px;">
                <i class="fas fa-exclamation-triangle mt-1" style="color:#f39c12; font-size:1.1rem;"></i>
                <div>
                    Payment for <strong>{{ $pay->child->name }}</strong> of
                    <strong>RM {{ number_format($pay->pay_amount, 2) }}</strong> is due on
                    <strong>{{ $pay->issue_date->addDays(30)->format('d M Y') }}</strong>.
                    Please make payment before the due date to avoid overdue penalties.
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Overdue Payment Alerts --}}
    @if($overduePayments->count() > 0)
    <div class="card mb-3" style="border-color:#e74c3c;">
        <div class="card-header" style="background:#fff3f3 !important; border-bottom-color:#e74c3c; color:#c0392b; font-family:'Syne',sans-serif; font-weight:700; padding:1rem 1.25rem;">
            <i class="fas fa-exclamation-circle me-2"></i> Overdue Payment Warning
        </div>
        <div class="card-body pb-2">
            @foreach($overduePayments as $pay)
            <div class="d-flex align-items-start gap-3 p-3 mb-2" style="background:#fff3f3; border:1.5px solid rgba(231,76,60,0.35); border-radius:10px;">
                <i class="fas fa-ban mt-1" style="color:#e74c3c; font-size:1.1rem;"></i>
                <div>
                    <strong>Overdue — {{ $pay->child->name }} (RM {{ number_format($pay->pay_amount, 2) }}).</strong>
                    Please do the payment immediately. If payment is not settled within 10 days of the due date,
                    a <strong>2% increment</strong> will be added to the payment amount as a penalty.
                    @if($pay->penalty_applied)
                    <br><span style="color:#c0392b; font-weight:700;">
                        <i class="fas fa-percent me-1"></i>2% penalty has already been applied. Updated amount: RM {{ number_format($pay->pay_amount, 2) }}
                    </span>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('payment.checkout', $pay->id) }}" class="btn btn-sm btn-danger">
                            <i class="fas fa-credit-card me-1"></i> Pay Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-bullhorn me-2"></i>Your Announcements</span>
            
            <form method="GET" action="{{ route('ann') }}" class="d-flex align-items-center">
                <div class="me-2">
                    <select name="filter_option" class="form-select form-select-sm">
                        <option value="">View all</option>
                        <option value="absence" {{ request('filter_option') == 'absence' ? 'selected' : '' }}>Absence</option>
                        <option value="delay" {{ request('filter_option') == 'delay' ? 'selected' : '' }}>Delay</option>
                    </select>
                </div>
                <div class="me-2">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('ann') }}" class="btn btn-secondary btn-sm ms-2">Reset</a>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 35%;">Content</th>
                            <th style="width: 15%;">Created At</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parentAnnouncements as $index => $announcement)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('ann.edit', $announcement->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('ann.destroy', $announcement->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-bus me-2"></i>Driver Announcements</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 25%;">Title</th>
                            <th style="width: 50%;">Content</th>
                            <th style="width: 20%;">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverAnnouncements as $index => $announcement)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold text-success">{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @elseif($userRole == 'D')
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user-circle me-2"></i>Your Driver Updates</span>
            
            <form method="GET" action="{{ route('ann') }}" class="d-flex align-items-center">
                <div class="me-2">
                    <select name="filter_option" class="form-select form-select-sm">
                        <option value="">View all</option>
                        <option value="absence" {{ request('filter_option') == 'absence' ? 'selected' : '' }}>Absence</option>
                        <option value="delay" {{ request('filter_option') == 'delay' ? 'selected' : '' }}>Delay</option>
                    </select>
                </div>
                <div class="me-2">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('ann') }}" class="btn btn-secondary btn-sm ms-2">Reset</a>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 20%;">Title</th>
                            <th style="width: 35%;">Content</th>
                            <th style="width: 15%;">Created At</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($driverAnnouncements as $index => $announcement)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('ann.edit', $announcement->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('ann.destroy', $announcement->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-users me-2"></i>Parent Updates</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="width: 7%;">No.</th>
                            <th style="width: 20%;">Parent Name</th>
                            <th style="width: 15%;">Title</th>
                            <th style="width: 50%;">Content</th>
                            <th style="width: 20%;">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parentAnnouncements as $index => $announcement)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold text-primary">{{ $announcement->user->name }}</td>
                                <td>{{ $announcement->title }}</td>
                                <td>{!! $announcement->content !!}</td>
                                <td>{{ $announcement->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No announcements available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection