@extends('layout.main-template')

@section('title', 'Driver List')

@section('content')

<h1 class="mt-4">Add Driver</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Driver List</li>
    </ol>

<div class="container mt-5">
<div class="form-check mb-4 d-flex justify-content-end">
    <input class="form-check-input" type="checkbox" id="filter-location" onchange="filterDrivers()">
    <label class="form-check-label" for="filter-location">
        Match location with driver's schedule
    </label>
</div>


<div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Driver List

            </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="driver-list">
                    @foreach ($drivers as $driver)
                        <tr data-locations="{{ json_encode($driver->schedule_locations) }}">
                            <td>{{ $driver->id }}</td>
                            <td>{{ $driver->user->name }}</td>
                            <td>{{ $driver->user->email }}</td>
                            <td>
                                <a href="{{ route('schedule', ['driver_id' => $driver->id, 'child_id' => $child->id]) }}" class="btn btn-link">
                                    View Schedule
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<script>
    function filterDrivers() {
        const filter = document.getElementById('filter-location').checked;
        const rows = document.querySelectorAll('#driver-list tr');
        const parentLocation = @json($parent_location);
        const schoolName = @json($child->school_name);

        rows.forEach(row => {
            const driverLocations = JSON.parse(row.getAttribute('data-locations'));
            const matches = filter 
                ? driverLocations.includes(parentLocation) || driverLocations.includes(schoolName)
                : true;
            row.style.display = matches ? '' : 'none';
        });
    }

    window.onload = function() {
    document.getElementById('filter-location').checked = false;
}
</script>
@endsection
