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
        --bg:         #f5f7fa;
        --border:     rgba(0,184,148,0.25);
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
        margin-top: 2rem;
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

    .table thead {
        background-color: var(--navy);
        color: var(--white);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 184, 148, 0.02);
    }

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
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title"><i class="fas fa-bus me-2"></i>Vehicle List</h2>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary px-4 py-2">
            <i class="fas fa-plus-circle me-1"></i> Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-2"></i>Company Owned Vehicles
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3" style="width:5%;">No.</th>
                            <th style="width:18%;">Vehicle Number</th>
                            <th style="width:20%;">Model Name</th>
                            <th style="width:16%;">Legal Document</th>
                            <th style="width:17%;">APAD Expiry</th>
                            <th style="width:18%;">Assigned Driver</th>
                            <th style="width:12%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $index => $vehicle)
                            <tr>
                                <td class="ps-3">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $vehicle->vehicle_number }}</td>
                                <td>{{ $vehicle->model_name }}</td>
                                <td>
                                    @if($vehicle->legal_document)
                                        <a href="{{ asset('storage/' . $vehicle->legal_document) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-file me-1"></i>View
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vehicle->apad_expiry_date)
                                        @php
                                            $expiry = \Carbon\Carbon::parse($vehicle->apad_expiry_date);
                                            $daysLeft = now()->diffInDays($expiry, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">Expired</span>
                                            <div style="font-size:0.78rem; color:#e74c3c;">{{ $expiry->format('d M Y') }}</div>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge bg-warning text-dark">Expiring soon</span>
                                            <div style="font-size:0.78rem; color:#e67e22;">{{ $expiry->format('d M Y') }}</div>
                                        @else
                                            <span class="badge bg-success">Valid</span>
                                            <div style="font-size:0.78rem; color:#27ae60;">{{ $expiry->format('d M Y') }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vehicle->driver && $vehicle->driver->user)
                                        <span class="badge" style="background-color: var(--emerald); font-size:0.85rem;">
                                            {{ $vehicle->driver->user->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this vehicle?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No vehicles added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
