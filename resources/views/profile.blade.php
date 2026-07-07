@extends('layout.main-template')

@section('content')
<style>
    :root {
        --emerald: #00b894;
        --emerald-dk: #007a63;
        --navy: #0a1628;
        --slate: #4a5568;
        --input-bg: #f8fafc;
    }

    .profile-container {
        padding: 2rem 0 5rem;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(0, 184, 148, 0.2);
        box-shadow: 0 15px 35px rgba(10, 22, 40, 0.1);
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--navy) 0%, #1a2a44 100%);
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    .profile-header h3 {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--emerald);
        letter-spacing: 2px;
        margin-top: 1rem;
        text-transform: uppercase;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        background: var(--emerald);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 4px solid rgba(255,255,255,0.1);
        font-size: 2.5rem;
        color: white;
    }

    .section-title {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        color: var(--navy);
        border-bottom: 2px solid var(--emerald);
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    .form-group label {
        font-weight: 600;
        color: var(--slate);
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
        margin-top: 1rem;
    }

    .form-control-plaintext {
        background-color: var(--input-bg) !important;
        border: 1px solid #e2e8f0 !important;
        padding: 0.75rem 1rem !important;
        border-radius: 10px !important;
        color: var(--navy) !important;
        font-weight: 500;
    }

    .btn-edit {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        color: white !important;
        border: none;
        padding: 0.6rem 2rem;
        border-radius: 10px;
        font-weight: 700;
        font-family: 'Syne', sans-serif;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 184, 148, 0.4);
    }

    .btn-remove {
        background-color: transparent;
        color: #ef4444;
        border: 1px solid #ef4444;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-remove:hover {
        background-color: #ef4444;
        color: white;
    }

    .child-box {
        background: #f1f5f9;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1rem;
        border-left: 4px solid var(--emerald);
    }
</style>

<div class="profile-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-circle">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3>Profile</h3>
                    <p class="text-white-50 mb-0">{{ Auth::user()->role === 'P' ? 'Parent Account' : 'Driver Account' }}</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    @if($user->role === 'A')
                        {{-- Admin Profile --}}
                        <div style="text-align: center; padding: 4rem;">
                            <h1>👨‍💼 Admin Dashboard</h1>
                            <h2>Welcome, {{ $userName }}!</h2>
                            <p>Manage your system settings and users.</p>
                            <a href="{{ route('admin.users') }}" class="btn btn-success mt-3">Manage Users</a>
                            <a href="{{ route('driver_verification') }}" class="btn btn-primary mt-3 ms-2">Driver Verification</a>
                        </div>
                    @endif
                    
                    @if (Auth::user()->role === 'P')
                        <h5 class="section-title">Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control-plaintext" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control-plaintext" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>Home Location</label>
                                <input type="text" class="form-control-plaintext" value="{{ $parent->location }}" readonly>
                            </div>
                        </div>

                        <h5 class="section-title mt-5">Children Information</h5>
                        @foreach ($children as $child)
                            <div class="child-box">
                                <div class="row">
                                    <div class="col-md-4 form-group mt-0">
                                        <label>Child Name</label>
                                        <input type="text" class="form-control-plaintext" value="{{ $child->name }}" readonly>
                                    </div>
                                    <div class="col-md-4 form-group mt-0">
                                        <label>School</label>
                                        <input type="text" class="form-control-plaintext" value="{{ $child->school_name }}" readonly>
                                    </div>
                                    <div class="col-md-4 form-group mt-0">
                                        <label>Assigned Driver</label>
                                        <input type="text" class="form-control-plaintext" value="{{ $child->driver->user->name ?? 'Not Assigned' }}" readonly style="color: var(--emerald) !important;">
                                    </div>
                                    <div class="col-md-6 form-group mt-0">
                                        <label>City</label>
                                        <input type="text" class="form-control-plaintext" value="{{ $child->city ?? '—' }}" readonly>
                                    </div>
                                    <div class="col-md-6 form-group mt-0">
                                        <label>District</label>
                                        <input type="text" class="form-control-plaintext" value="{{ $child->district ?? '—' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (Auth::user()->role === 'D')
                        <h5 class="section-title">Driver Information</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control-plaintext" value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email Address</label>
                                <input type="email" class="form-control-plaintext" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Vehicle Registration (VRN)</label>
                                <input type="text" class="form-control-plaintext" value="{{ $driver->VRN }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Active Passengers</label>
                                <input type="text" class="form-control-plaintext" value="{{ $passengersCount }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>City</label>
                                <input type="text" class="form-control-plaintext" value="{{ $driver->city ?? '—' }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>District</label>
                                <input type="text" class="form-control-plaintext" value="{{ $driver->district ?? '—' }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Bank Name</label>
                                <input type="text" class="form-control-plaintext" value="{{ $driver->bank_name ?? '—' }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Account Number</label>
                                <input type="text" class="form-control-plaintext" value="{{ $driver->bank_account_number ?? '—' }}" readonly>
                            </div>
                        </div>
                    @endif

                    <div class="mt-5 pt-4 d-flex justify-content-end align-items-center border-top">
                        <a href="{{ route('profile.edit') }}" class="btn btn-edit">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
@endsection