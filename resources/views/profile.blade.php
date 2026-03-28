@extends('layout.main-template') <!-- Assuming 'app.blade.php' is your main layout -->

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-light text-center">
            <h3>PROFILE</h3>
        </div>
        <div class="card-body">
            @if (Auth::user()->role === 'P')
                <!-- Parent Profile Section -->
                <h5 class="card-title">Parent Profile</h5>
                <div class="form-group">
                    <label for="parentName">Name:</label>
                    <input type="text" class="form-control" id="parentName" value="{{ $user->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="parentEmail">Email:</label>
                    <input type="email" class="form-control" id="parentEmail" value="{{ $user->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="parentLocation">Location:</label>
                    <input type="text" class="form-control" id="parentLocation" value="{{ $parent->location }}" readonly>
                </div>

                <h5 class="card-title mt-4">Children Information</h5>
                @foreach ($children as $child)
                    <div class="form-group">
                        <label for="childName">Child Name:</label>
                        <input type="text" class="form-control" id="childName" value="{{ $child->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="childSchool">School:</label>
                        <input type="text" class="form-control" id="childSchool" value="{{ $child->school_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="childDriver">Driver:</label>
                        <input type="text" class="form-control" id="childDriver" value="{{ $child->driver->user->name ?? 'Not Assigned' }}" readonly>
                    </div>
                @endforeach
                <div class="mt-4 d-flex justify-content-end">
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit</a>

    <form id="remove-account-form" action="{{ route('profile.remove') }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger ml-2" onclick="confirmRemoval()">Remove Account</button>
    </form>
</div>

<script>
    function confirmRemoval() {
        if (confirm("Are you sure you want to remove your account? This action cannot be undone.")) {
            document.getElementById('remove-account-form').submit();
        }
    }
</script>

            @endif

            @if (Auth::user()->role === 'D')
                <!-- Driver Profile Section -->
                <h5 class="card-title">Driver Profile</h5>
                <div class="form-group">
                    <label for="driverName">Name:</label>
                    <input type="text" class="form-control" id="driverName" value="{{ $user->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="driverEmail">Email:</label>
                    <input type="email" class="form-control" id="driverEmail" value="{{ $user->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="vehicleInfo">Vehicle Information:</label>
                    <input type="text" class="form-control" id="vehicleInfo" value="{{ $driver->VRN }}" readonly>
                </div>
                <div class="form-group">
                    <label for="passengerCount">Number of Passengers:</label>
                    <input type="text" class="form-control" id="passengerCount" value="{{ $passengersCount }}" readonly>
                </div>
                <div class="mt-4 d-flex justify-content-end">
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit</a>

    <form id="remove-account-form" action="{{ route('profile.remove') }}" method="POST" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger ml-2" onclick="confirmRemoval()">Remove Account</button>
    </form>
</div>

<script>
    function confirmRemoval() {
        if (confirm("Are you sure you want to remove your account? This action cannot be undone.")) {
            document.getElementById('remove-account-form').submit();
        }
    }
</script>

            @endif
        </div>
    </div>
</div>
@endsection
