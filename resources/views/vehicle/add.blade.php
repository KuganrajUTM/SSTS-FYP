@extends('layout.main-template')

@section('content')
<style>
    :root {
        --emerald:    #00b894;
        --emerald-dk: #007a63;
        --emerald-lt: #e6f9f5;
        --navy:       #0a1628;
        --border:     rgba(0,184,148,0.25);
        --white:      #ffffff;
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
        box-shadow: 0 10px 30px rgba(0,184,148,0.05);
        background: var(--white);
    }

    .card-header {
        background-color: var(--emerald-lt) !important;
        border-bottom: 1.5px solid var(--border);
        color: var(--emerald-dk);
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        padding: 1rem 1.25rem;
    }

    .form-label { font-weight: 600; color: var(--navy); }

    .form-control:focus, .form-select:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none;
        font-family: 'Syne', sans-serif;
        font-weight: 600;
    }

    .btn-primary:hover { background: var(--emerald-dk); }
</style>

<div class="container mt-4" style="max-width: 700px;">
    <h2 class="page-title mb-4"><i class="fas fa-plus-circle me-2"></i>Add Vehicle</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header"><i class="fas fa-bus me-2"></i>Vehicle Details</div>
        <div class="card-body p-4">
            <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Vehicle Number (Plate)</label>
                    <input type="text" name="vehicle_number" class="form-control" placeholder="e.g. WXY 1234" value="{{ old('vehicle_number') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Model Name</label>
                    <input type="text" name="model_name" class="form-control" placeholder="e.g. Toyota HiAce" value="{{ old('model_name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Legal Document <span class="text-muted fw-normal">(PDF / Image, max 5MB)</span></label>
                    <input type="file" name="legal_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="mb-3">
                    <label class="form-label">APAD Document Expiry Date <span class="text-danger">*</span></label>
                    <input type="date" name="apad_expiry_date" class="form-control" value="{{ old('apad_expiry_date') }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Assign Driver <span class="text-muted fw-normal">(Optional)</span></label>
                    <select name="driver_id" class="form-select">
                        <option value="">— No driver assigned —</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->user->name ?? 'Driver #'.$driver->id }} — {{ $driver->VRN }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Save Vehicle
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
