@extends('layout.main-template') <!-- Assuming 'main-template.blade.php' is your main layout -->

@section('title', 'Edit Profile')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-light text-center">
            <h3>Edit Profile</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                @if ($user->role === 'P')
                    <!-- Parent Profile Editing -->
                    <h5 class="card-title">Parent Profile</h5>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $parent->location) }}">
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <h5 class="mt-4">Children Information</h5>
                    <div id="children-list">
                        @foreach ($children as $child)
                            <div class="child-entry border rounded p-3 mb-3">
                                <div class="form-group">
                                    <label>Child Name:</label>
                                    <input type="text" name="children[{{ $child->id }}][name]" class="form-control" value="{{ old('children.'.$child->id.'.name', $child->name) }}">
                                    @error('children.'.$child->id.'.name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>School Name:</label>
                                    <input type="text" name="children[{{ $child->id }}][school_name]" class="form-control" value="{{ old('children.'.$child->id.'.school_name', $child->school_name) }}">
                                    @error('children.'.$child->id.'.school_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
    <label>Driver:</label>
    <a href="{{ route('list', ['child_id' => $child->id]) }}" class="btn btn-secondary">Choose Driver</a>

    @if ($child->driver)
        <p class="mt-2"><strong>Current Driver:</strong> {{ $child->driver->name }}</p>
    @endif
</div>

                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-child" class="btn btn-success mt-3">Add Child</button>
                @endif

                @if ($user->role === 'D')
                    <!-- Driver Profile Editing -->
                    <h5 class="card-title">Driver Profile</h5>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="vehicle_info">Vehicle Information:</label>
                        <input type="text" name="vehicle_info" class="form-control" value="{{ old('vehicle_info', $driver->VRN) }}">
                        @error('vehicle_info')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                @endif

                <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-child').addEventListener('click', function () {
        const container = document.getElementById('children-list');
        const childCount = container.children.length;
        const newChild = `
            <div class="child-entry border rounded p-3 mb-3">
                <div class="form-group">
                    <label>Child Name:</label>
                    <input type="text" name="children[new_${childCount}][name]" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label>School Name:</label>
                    <input type="text" name="children[new_${childCount}][school_name]" class="form-control" value="">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newChild);
    });
</script>
@endsection
