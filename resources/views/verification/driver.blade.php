@extends('layout.main-template')

@section('content')

<style>
    .overlay-notification {
        position: fixed;
        top: 20px; left: 50%;
        transform: translateX(-50%);
        background-color: #00b894;
        color: #fff;
        padding: 14px 24px;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,184,148,0.3);
        z-index: 1050;
        display: none;
        font-size: 0.95rem;
        font-weight: 600;
        animation: fadeInOut 5s forwards;
    }
    @keyframes fadeInOut {
        0%   { opacity:0; transform:translateY(-20px) translateX(-50%); }
        10%  { opacity:1; transform:translateY(0) translateX(-50%); }
        90%  { opacity:1; transform:translateY(0) translateX(-50%); }
        100% { opacity:0; transform:translateY(-20px) translateX(-50%); }
    }
</style>

@if(session('success'))
    <div class="overlay-notification" id="successNotification">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

<h1 class="mt-4"><i class="fas fa-user-check me-2"></i>Driver Verification</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Verification</li></ol>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list me-2"></i>Driver List</span>
        <div>
            <form method="GET" action="{{ route('driver_verification') }}" class="d-inline">
                <label class="me-1 text-white small">Filter:</label>
                <select name="status" class="form-select form-select-sm d-inline" style="width:auto;" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Pending"  {{ request('status') == 'Pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Driver</th>
                        <th>Bank Details</th>
                        <th>Documents</th>
                        <th>License Expiry</th>
                        <th>Status</th>
                        <th>Change Status</th>
                        <th>Rejection Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $index => $driver)
                    <tr>
                        <td class="align-middle">{{ $index + 1 }}</td>

                        <td class="align-middle fw-semibold">
                            <i class="fas fa-user-circle me-1 text-muted"></i>
                            {{ $driver->user->name ?? 'Unknown' }}
                        </td>

                        <td class="align-middle">
                            @if($driver->bank_name)
                                <div class="fw-semibold" style="font-size:0.88rem;">{{ $driver->bank_name }}</div>
                                <div class="text-muted" style="font-size:0.8rem;">{{ $driver->bank_account_number }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="align-middle">
                            <div class="d-flex flex-column gap-1">
                                <a href="{{ route('view-pdf', ['docs_name' => 'SPAD', 'id' => $driver->id]) }}" target="_blank" class="btn btn-sm btn-primary" style="font-size:0.78rem; white-space:nowrap;">
                                    <i class="fas fa-file-alt me-1"></i>SPAD
                                </a>
                                <a href="{{ route('view-pdf', ['docs_name' => 'LIC', 'id' => $driver->id]) }}" target="_blank" class="btn btn-sm btn-secondary" style="font-size:0.78rem; white-space:nowrap;">
                                    <i class="fas fa-id-card me-1"></i>License
                                </a>
                            </div>
                        </td>

                        <td class="align-middle">
                            @php $expiry = $driver->verification->license_expiry_date ?? null; @endphp
                            @if($expiry)
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($expiry);
                                    $daysLeft = now()->diffInDays($expiryDate, false);
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($daysLeft <= 30)
                                    <span class="badge bg-warning text-dark">Expiring Soon</span>
                                @else
                                    <span class="badge bg-success">Valid</span>
                                @endif
                                <div class="text-muted" style="font-size:0.78rem;">{{ $expiryDate->format('d M Y') }}</div>
                            @else
                                <span class="text-muted small">Not set</span>
                            @endif
                            <button class="btn btn-outline-secondary btn-sm mt-1" onclick="openExpiryModal({{ $driver->id }}, '{{ $expiry ?? '' }}')" title="Edit expiry date">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>

                        <td class="align-middle">
                            @php $status = $driver->verification->ver_status ?? 'Pending'; @endphp
                            @if($status == 'Approved')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                            @elseif($status == 'Rejected')
                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                            @endif
                        </td>

                        <td class="align-middle">
                            <form id="status-form-{{ $driver->id }}" method="POST" action="{{ route('update_verification', $driver->id) }}">
                                @csrf
                                <select name="status"
                                        class="form-select form-select-sm"
                                        id="status-{{ $driver->id }}"
                                        data-original-status="{{ $driver->verification->ver_status ?? 'Pending' }}"
                                        onchange="confirmStatusChange('{{ $driver->id }}')"
                                        style="width:130px;">
                                    <option value="Pending"  {{ ($driver->verification->ver_status ?? 'Pending') == 'Pending'  ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ ($driver->verification->ver_status ?? 'Pending') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Rejected" {{ ($driver->verification->ver_status ?? 'Pending') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </td>

                        <td class="align-middle" style="max-width:160px;">
                            <span class="text-muted small">{{ $driver->verification->rej_reason ?? '—' }}</span>
                        </td>

                        <td class="align-middle">
                            <form action="{{ route('verification.delete', $driver->id) }}" method="POST" onsubmit="return confirm('Delete this driver permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No drivers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- License Expiry Modal --}}
<div class="modal fade" id="expiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:1.5px solid rgba(0,184,148,0.25);">
            <div class="modal-header" style="background:#e6f9f5; border-bottom:1.5px solid rgba(0,184,148,0.25);">
                <h5 class="modal-title" style="font-weight:700; color:#0a1628;">
                    <i class="fas fa-id-card me-2" style="color:#00b894;"></i>Set License Expiry Date
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="expiryForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label class="form-label fw-semibold">License Expiry Date</label>
                    <input type="date" name="license_expiry_date" id="expiryDateInput" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Rejection Reason Modal --}}
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:1.5px solid rgba(231,76,60,0.25);">
            <div class="modal-header" style="background:#fdf0ee; border-bottom:1.5px solid rgba(231,76,60,0.25);">
                <h5 class="modal-title" style="font-weight:700; color:#0a1628;">
                    <i class="fas fa-times-circle me-2 text-danger"></i>Rejection Reason
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <label class="form-label fw-semibold">Please provide a reason <span class="text-danger">*</span></label>
                <textarea id="rejection-reason-input" class="form-control" rows="4" placeholder="e.g. Documents incomplete, license expired..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="save-reason-button" class="btn btn-danger">
                    <i class="fas fa-ban me-1"></i>Confirm Rejection
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notification = document.getElementById('successNotification');
    if (notification) {
        notification.style.display = 'block';
        setTimeout(() => notification.style.display = 'none', 5000);
    }
});

function openExpiryModal(driverId, currentExpiry) {
    document.getElementById('expiryForm').action = '/verification/license-expiry/' + driverId;
    document.getElementById('expiryDateInput').value = currentExpiry;
    new bootstrap.Modal(document.getElementById('expiryModal')).show();
}

function confirmStatusChange(driverId) {
    const statusSelect = document.getElementById(`status-${driverId}`);
    const newStatus = statusSelect.value;
    const form = document.getElementById(`status-form-${driverId}`);
    const originalStatus = statusSelect.dataset.originalStatus || 'Pending';

    if (newStatus === 'Rejected') {
        const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        rejectionModal.show();
        document.getElementById('rejectionModal').setAttribute('data-driver-id', driverId);

        document.getElementById('save-reason-button').onclick = function() {
            const reason = document.getElementById('rejection-reason-input').value.trim();
            if (!reason) { alert('Please provide a reason for rejection.'); return; }
            let input = form.querySelector('input[name="rejection_reason"]');
            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'rejection_reason';
                form.appendChild(input);
            }
            input.value = reason;
            form.submit();
        };
    } else {
        if (confirm(`Change status to "${newStatus}"?`)) {
            form.submit();
        } else {
            statusSelect.value = originalStatus;
        }
    }
}
</script>

@endsection
