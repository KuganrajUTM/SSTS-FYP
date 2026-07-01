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
    .stat-card .card-footer { color: #fff; }

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
        <h2 class="page-title">Payment Records</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card pending">
                <div class="card-body">Pending Payment</div>
                <div class="card-footer">{{ $paymentCounts->pending_count }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card paid">
                <div class="card-body">Successful Payment</div>
                <div class="card-footer">{{ $paymentCounts->paid_count }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card overdue">
                <div class="card-body">Overdue Payment</div>
                <div class="card-footer">{{ $paymentCounts->overdue_count }}</div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap justify-content-end mb-3 gap-2">
        <form method="GET" action="{{ route('admin.payments') }}" class="d-flex flex-wrap align-items-center gap-2">
            <label class="mb-0">Driver:</label>
            <select name="driver_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                <option value="">All Drivers</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                        {{ $driver->user->name }}
                    </option>
                @endforeach
            </select>

            <label class="mb-0">Status:</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                <option value="">All Status</option>
                <option value="Pending"  {{ request('status') == 'Pending'  ? 'selected' : '' }}>Pending</option>
                <option value="Paid"     {{ request('status') == 'Paid'     ? 'selected' : '' }}>Paid</option>
                <option value="Overdue"  {{ request('status') == 'Overdue'  ? 'selected' : '' }}>Overdue</option>
            </select>

            <label class="mb-0">Month:</label>
            <select name="month" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                <option value="">All Months</option>
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endforeach
            </select>

            <label class="mb-0">Year:</label>
            <select name="year" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                <option value="">All Years</option>
                @foreach (range(now()->year, now()->year - 5) as $yr)
                    <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> All Payment Records
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Parent</th>
                        <th>Child</th>
                        <th>Driver</th>
                        <th>Amount (RM)</th>
                        <th>Date</th>
                        <th>Payment Status</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Parent</th>
                        <th>Child</th>
                        <th>Driver</th>
                        <th>Amount (RM)</th>
                        <th>Date</th>
                        <th>Payment Status</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($payments as $index => $pay)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pay->parent->user->name ?? '-' }}</td>
                        <td>{{ $pay->child->name ?? '-' }}</td>
                        <td>{{ $pay->driver->user->name ?? '-' }}</td>
                        <td>{{ number_format($pay->pay_amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($pay->issue_date)->format('d-m-Y') }}</td>
                        <td>
                            @if ($pay->pay_status === 'Paid')
                                <span class="badge bg-success text-white">Paid</span>
                            @elseif ($pay->pay_status === 'Overdue')
                                <span class="badge bg-danger text-white">Overdue</span>
                            @else
                                <span class="badge bg-warning text-white">Pending</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if ($pay->pay_status !== 'Paid')
                                <select class="form-select form-select-sm d-inline-block" style="width:120px;"
                                        onchange="openConfirmationModal('{{ $pay->id }}', this.value)">
                                    <option value="{{ $pay->pay_status }}" selected>{{ $pay->pay_status }}</option>
                                    <option value="Paid">Paid (Cash)</option>
                                </select>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Repeat-Offender Alerts --}}
@if($repeatOffenders->count() > 0)
<div class="card mt-2" style="border-color:#e74c3c;">
    <div class="card-header" style="background:#fff3f3 !important; border-bottom-color:#e74c3c; color:#c0392b;">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Parents with Repeated Overdue Payments (3 or more times)
    </div>
    <div class="card-body">
        @foreach($repeatOffenders as $child)
        <div class="d-flex justify-content-between align-items-center p-3 mb-2" style="background:#fff3f3; border:1.5px solid rgba(231,76,60,0.3); border-radius:10px;">
            <div>
                <span class="fw-bold">{{ $child->parent->user->name }}</span>
                <span class="text-muted mx-2">—</span> Child: <strong>{{ $child->name }}</strong>
                <span class="badge bg-danger ms-2">{{ $child->overdue_count }} overdue incidents</span>
            </div>
            <button class="btn btn-danger btn-sm"
                onclick="openTakeAction(
                    {{ $child->id }},
                    '{{ addslashes($child->parent->user->name) }}',
                    '{{ addslashes($child->name) }}',
                    '{{ addslashes($child->parent->phone ?? 'N/A') }}',
                    '{{ addslashes($child->parent->user->email) }}',
                    '{{ addslashes(implode(', ', array_filter([$child->parent->location, $child->parent->city, $child->parent->district]))) }}'
                )">
                <i class="fas fa-gavel me-1"></i> Take Action
            </button>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Take Action Modal --}}
<div class="modal fade" id="takeActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px; overflow:hidden;">
            <div class="modal-header" style="background:#fff3f3; border-bottom-color:#e74c3c; color:#c0392b;">
                <h5 class="modal-title"><i class="fas fa-gavel me-2"></i>Take Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold mb-3" style="color:#0a1628;">Parent Contact Details</h6>
                <table class="table table-borderless table-sm mb-3">
                    <tr>
                        <td class="fw-bold text-muted" style="width:35%;">Parent Name</td>
                        <td id="ta-parent-name" class="fw-bold">—</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Child</td>
                        <td id="ta-child-name">—</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Mobile Number</td>
                        <td id="ta-phone">—</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Email</td>
                        <td id="ta-email">—</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-muted">Address</td>
                        <td id="ta-address">—</td>
                    </tr>
                </table>
                <hr>
                <p class="fw-bold mb-1" style="color:#e74c3c;">
                    <i class="fas fa-user-minus me-1"></i> Remove Child from Driver
                </p>
                <p class="text-muted small mb-0">
                    This will unassign the child from their current driver.
                    The child's record will remain and can be reassigned later.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="removeChildForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-user-minus me-1"></i> Remove Child
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openTakeAction(childId, parentName, childName, phone, email, address) {
    document.getElementById('ta-parent-name').textContent = parentName;
    document.getElementById('ta-child-name').textContent  = childName;
    document.getElementById('ta-phone').textContent       = phone || 'N/A';
    document.getElementById('ta-email').textContent       = email;
    document.getElementById('ta-address').textContent     = address || 'N/A';
    document.getElementById('removeChildForm').action     = '/admin/child/' + childId + '/unassign';
    new bootstrap.Modal(document.getElementById('takeActionModal')).show();
}
</script>

{{-- Cash payment confirmation modal --}}
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Cash Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Mark this payment as <strong id="newStatus"></strong> (Cash)?
            </div>
            <div class="modal-footer">
                <form id="statusUpdateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="modalPayStatus" name="pay_status">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-confirm">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openConfirmationModal(paymentId, newStatus) {
        document.getElementById('newStatus').innerText = newStatus;
        document.getElementById('modalPayStatus').value = newStatus;
        document.getElementById('statusUpdateForm').action = '/payment/status/' + paymentId;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    }
</script>

@endsection
