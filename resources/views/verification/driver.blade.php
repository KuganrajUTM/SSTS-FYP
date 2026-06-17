@extends('layout.main-template')

@section('content')

<style>
    .btn-fixed-width {
        width: 150px;
        text-align: center;
    }
    .overlay-notification {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #28a745;
        color: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        z-index: 1050;
        display: none;
        font-size: 1rem;
        font-weight: 500;
        animation: fadeInOut 5s forwards;
    }

    @keyframes fadeInOut {
        0% {
            opacity: 0;
            transform: translateY(-20px) translateX(-50%);
        }
        10% {
            opacity: 1;
            transform: translateY(0) translateX(-50%);
        }
        90% {
            opacity: 1;
            transform: translateY(0) translateX(-50%);
        }
        100% {
            opacity: 0;
            transform: translateY(-20px) translateX(-50%);
        }
    }
</style>

<h1 class="mt-4">Driver Verification</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Verification</li>
</ol>
<div class="mb-3 text-end">
    <form method="GET" action="{{ route('driver_verification') }}" class="d-inline">
        <label for="status-filter" class="form-label me-2">Filter by Status:</label>
        <select name="status" id="status-filter" class="form-select d-inline" onchange="this.form.submit()" style="display: inline-block; width: auto;">
            <option value="">All</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </form>
</div>

<div class="container my-5">
    @if (session('success'))
        <div class="overlay-notification" id="successNotification">
            {{ session('success') }}
        </div>
    @endif
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Driver List
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Driver</th>
                    <th>Bank Details</th>
                    <th>Documents</th>
                    <th>License Expiry</th>
                    <th>Verification Status</th>
                    <th>Action</th>
                    <th>Rejection Reason</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
               @foreach ($drivers as $index => $driver)
                    <tr id="driver-{{ $driver->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $driver->user->name ?? 'Unknown' }}</td>
                        <td>
                            @if($driver->bank_name)
                                <strong>{{ $driver->bank_name }}</strong><br>
                                <span class="text-muted" style="font-size:0.85rem;">{{ $driver->bank_account_number }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            {{-- SPAD and License links using $driver->id --}}
                            <a href="{{ route('view-pdf', ['docs_name' => 'SPAD', 'id' => $driver->id]) }}" target="_blank" class="btn btn-primary btn-sm mb-2 btn-fixed-width">
                                <i class="fas fa-file-alt"></i> View SPAD
                            </a>
                            <br>
                            <a href="{{ route('view-pdf', ['docs_name' => 'LIC', 'id' => $driver->id]) }}" target="_blank" class="btn btn-secondary btn-sm btn-fixed-width">
                                <i class="fas fa-id-card"></i> View License
                            </a>
                        </td>

                        {{-- License Expiry --}}
                        <td>
                            @php $expiry = $driver->verification->license_expiry_date ?? null; @endphp
                            @if($expiry)
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($expiry);
                                    $daysLeft = now()->diffInDays($expiryDate, false);
                                @endphp
                                @if($daysLeft < 0)
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($daysLeft <= 30)
                                    <span class="badge bg-warning text-dark">Expiring soon</span>
                                @else
                                    <span class="badge bg-success">Valid</span>
                                @endif
                                <div style="font-size:0.78rem; color:#636e72;">{{ $expiryDate->format('d M Y') }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                            <button class="btn btn-outline-secondary btn-sm mt-1" onclick="openExpiryModal({{ $driver->id }}, '{{ $expiry ?? '' }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>

                        {{-- Verification Status --}}
                        <td>
                            @php $status = $driver->verification->ver_status ?? 'Pending'; @endphp
                            @if($status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @elseif($status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-white">Pending</span>
                            @endif
                        </td>

                        <td>
                            <form id="status-form-{{ $driver->id }}" method="POST" action="{{ route('update_verification', $driver->id) }}">
                                @csrf
                                <select name="status" 
                                        class="form-select form-select-sm" 
                                        id="status-{{ $driver->id }}" 
                                        data-original-status="{{ $driver->verification->ver_status ?? 'Pending' }}"
                                        onchange="confirmStatusChange('{{ $driver->id }}')" 
                                        style="width: auto;">
                                    <option value="Pending" {{ ($driver->verification->ver_status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ ($driver->verification->ver_status ?? 'Pending') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Rejected" {{ ($driver->verification->ver_status ?? 'Pending') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </form>
                        </td>

                        <td>{{ $driver->verification->rej_reason ?? 'N/A' }}</td>

                        <td>
                            <form action="{{ route('verification.delete', $driver->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for license expiry date -->
<div class="modal fade" id="expiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:1.5px solid rgba(0,184,148,0.25);">
            <div class="modal-header" style="background:#e6f9f5; border-bottom:1.5px solid rgba(0,184,148,0.25);">
                <h5 class="modal-title" style="font-weight:700; color:#0a1628;"><i class="fas fa-id-card me-2" style="color:#00b894;"></i>Set License Expiry Date</h5>
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

<!-- Modal for rejection reason -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">Rejection Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="rejection-reason-input">
                    Please provide the reason for rejection: 
                    <span class="text-danger">*</span>
                </label>
                <textarea id="rejection-reason-input" class="form-control" name="rejection" rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" id="save-reason-button" class="btn btn-success">Save Reason</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show success notification
    const notification = document.getElementById('successNotification');
    if (notification) {
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
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
    
    // Get the original status from a data attribute (we'll add this to the select)
    const originalStatus = statusSelect.dataset.originalStatus || 'Pending';

    if (newStatus === "Rejected") {
        // Show rejection modal
        const rejectionModal = new bootstrap.Modal(document.getElementById("rejectionModal"));
        rejectionModal.show();

        // Store driverId in modal for later use
        document.getElementById("rejectionModal").setAttribute('data-driver-id', driverId);
        
        // Handle save reason button
        document.getElementById("save-reason-button").onclick = function() {
            const rejectionReason = document.getElementById("rejection-reason-input").value.trim();
            if (rejectionReason) {
                // Add rejection reason to form
                let rejectionInput = form.querySelector('input[name="rejection_reason"]');
                if (!rejectionInput) {
                    rejectionInput = document.createElement("input");
                    rejectionInput.type = "hidden";
                    rejectionInput.name = "rejection_reason";
                    form.appendChild(rejectionInput);
                }
                rejectionInput.value = rejectionReason;
                
                // Submit form
                form.submit();
            } else {
                alert("Please provide a reason for rejection.");
            }
        };
    } else {
        // For Approved/Pending - direct confirmation
        if (confirm(`Are you sure you want to change the status to "${newStatus}"?`)) {
            form.submit();
        } else {
            // Revert to original status
            statusSelect.value = originalStatus;
        }
    }
}
</script>

@endsection
