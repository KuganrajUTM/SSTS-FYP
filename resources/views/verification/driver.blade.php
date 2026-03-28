@extends('layout.main-template')

@section('content')
    <h1 class="mt-4">Driver Verification</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Verification</li>
    </ol>

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
                        <th>Documents</th>
                        <th>Verification Status</th>
                        <th>Action</th>
                        <th>Rejection Reason</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $driver)
                        <tr id="driver-{{ $driver->id }}">
                            <td>{{ $driver->id }}</td>
                            <td>{{ $driver->user->name ?? 'Unknown' }}</td>
                            <td>license.pdf</td>
                            <td>
                                @if($driver->status == 'rejected')
                                    <span class="badge bg-danger text-white badge-lg">Rejected</span>
                                @elseif($driver->status == 'approved')
                                    <span class="badge bg-success text-white badge-lg">Approved</span>
                                @else
                                    <span class="badge bg-warning text-white badge-lg">Pending</span>
                                @endif
                            </td>
                            <td>
                                <select id="status-select-{{ $driver->id }}" class="form-select" onchange="handleStatusChange({{ $driver->id }})">
                                    <option value="pending" {{ $driver->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $driver->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $driver->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </td>
                            <td id="rejection-reason-{{ $driver->id }}">{{ $driver->rejection_reason ?? 'N/A' }}</td>
                            <td>
                            <button class="btn btn-danger" onclick="deleteDriver({{ $driver->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for rejection reason -->
    <!-- Commented out modal for rejection reason -->
    <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionModalLabel">Rejection Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="rejection-reason-input">Please provide the reason for rejection:</label>
                    <textarea id="rejection-reason-input" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="save-reason-button" class="btn btn-primary">Save Reason</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function handleStatusChange(driverId) {
            const statusSelect = document.getElementById(`status-select-${driverId}`);
            const selectedStatus = statusSelect.value;
            if(selectedStatus=='rejected'){
                const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
                rejectionModal.show();
                document.getElementById('save-reason-button').onclick = function() {
                    const rejectionReason = document.getElementById('rejection-reason-input').value.trim();
                    if (rejectionReason) {
                        // Save the rejection reason and update status
                        saveRejectionReason(driverId, rejectionReason, rejectionModal);
                        updateStatus(driverId, selectedStatus);
                    } else {
                        alert('Please provide a reason for rejection.');
                    }
                }
            }
            else{
                // If the selected status is not rejected, directly update the status
                updateStatus(driverId, selectedStatus);
                const statusCell = document.querySelector(`#datatablesSimple tbody tr:nth-child(${driverId}) td:nth-child(6)`);
                    const rejectionModal = bootstrap.Modal.getInstance(document.getElementById('rejectionModal'));
                    if (statusCell) {
                        statusCell.innerHTML ='N/A';
                    }
            }
        }

        // Function to send an AJAX request to update the status
        function updateStatus(driverId, status) {
            fetch(`/EduTransit/public/verification/status/${driverId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF token for Laravel
                },
                body: JSON.stringify({ status: status }), // Send the status in the request body
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status updated successfully.');
                    // Update the display with the appropriate badge
                    const statusCell = document.querySelector(`#datatablesSimple tbody tr:nth-child(${driverId}) td:nth-child(4)`);
                    statusCell.innerHTML = getStatusBadge(status);

                    // Optionally, update the status in the dropdown to match the change
                    const statusSelect = document.getElementById(`status-select-${driverId}`);
                    statusSelect.value = status;
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        }

        function getStatusBadge(status) {
            switch (status) {
                case 'approved':
                    return '<span class="badge bg-success text-white badge-lg">Approved</span>';
                case 'rejected':
                    return '<span class="badge bg-danger text-white badge-lg">Rejected</span>';
                case 'pending':
                default:
                    return '<span class="badge bg-warning text-white badge-lg">Pending</span>';
            }
        }

        // Commented out functions related to rejection reason
        
        function saveRejectionReason(driverId, rejectionReason) {
            console.log('Saving rejection reason for driver ID:', driverId, 'with reason:', rejectionReason); 

            fetch(`/EduTransit/public/verification/rejection/${driverId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ rejection_reason: rejectionReason }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusCell = document.querySelector(`#datatablesSimple tbody tr:nth-child(${driverId}) td:nth-child(6)`);
                    const rejectionModal = bootstrap.Modal.getInstance(document.getElementById('rejectionModal'));
                    if (statusCell) {
                        statusCell.innerHTML =rejectionReason;
                    }
                    rejectionModal.hide();
                    alert('Rejection reason saved successfully.');
                } else {
                    alert('Failed to save rejection reason.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred.');
            });
        }
        

    
function deleteDriver(driverId) {
    if (confirm('Are you sure you want to delete this driver?')) {
        const row = document.querySelector(`button[onclick="deleteDriver(${driverId})"]`).closest('tr'); 
        row.remove();
        fetch(`/EduTransit/public/verification/delete/${driverId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

@endsection
