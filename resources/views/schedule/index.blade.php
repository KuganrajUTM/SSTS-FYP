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
                            <th>Time Slot</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monThuSchedules as $schedule)
                            <tr>
                                <td class="bg-dark text-white">{{ $schedule->time_slot }}</td>
                                <td>{{ $schedule->location }}</td>
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
                            <th>Time Slot</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friSchedules as $schedule)
                            <tr>
                                <td class="bg-dark text-white">{{ $schedule->time_slot }}</td>
                                <td>{{ $schedule->location }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @if ($monThuSchedules->isNotEmpty() || $friSchedules->isNotEmpty())
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('schedules.edit') }}" class="btn btn-warning">Edit Schedules</a>
            <form action="{{ route('schedules.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all schedules? This cannot be undone!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete All Schedules</button>
            </form>
        </div>
    @endif

    @if ($friSchedules->isEmpty())
    <div class="text-center mt-4">
        <a href="{{ route('Add_Schedule') }}" class="btn btn-primary">Add Schedule</a>
    </div>
    @endif
</div>
@endsection
