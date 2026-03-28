@extends('layout.main-template')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Driver's Schedule</h1>

    <div class="row">
        <!-- Monday - Thursday Table -->
        <div class="col-md-6">
            <h2 class="text-center">Monday - Thursday</h2>
            @if ($monThuSchedules->isEmpty())
                <div class="alert alert-info text-center">
                    No schedules available for Monday - Thursday.
                </div>
            @else
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50%;">Time Slot</th>
                            <th style="width: 50%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monThuSchedules as $schedule)
                            <tr>
                                <td class="text-white" style="background-color: #212121;">{{ $schedule->time_slot }}</td>
                                <td class="text-dark" style="background-color: #f4f4f4;">{{ $schedule->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Friday Table -->
        <div class="col-md-6">
            <h2 class="text-center">Friday</h2>
            @if ($friSchedules->isEmpty())
                <div class="alert alert-info text-center">
                    No schedules available for Friday.
                </div>
            @else
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 50%;">Time Slot</th>
                            <th style="width: 50%;">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friSchedules as $schedule)
                            <tr>
                                <td class="text-white" style="background-color: #212121;">{{ $schedule->time_slot }}</td>
                                <td class="text-dark" style="background-color: #f4f4f4;">{{ $schedule->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Assign Driver to Child Button -->
    <div class="text-center mt-4">
        <form action="{{ route('assign.driver', ['child_id' => $child_id, 'driver_id' => $driver->id]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Assign Driver to Child</button>
        </form>
    </div>
</div>
@endsection
