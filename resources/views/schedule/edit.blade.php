@extends('layout.main-template')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Edit Schedule</h1>

    <form action="{{ route('schedules.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Monday - Thursday Table -->
            <div class="col-md-6">
                <h2 class="text-center">Monday - Thursday</h2>
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Time Slot</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monThuTimeSlots as $timeSlot)
                            @php
                                $schedule = $monThuSchedules->where('time_slot', $timeSlot)->first();
                            @endphp
                            <tr>
                                <td class="bg-dark text-white">{{ $timeSlot }}</td>
                                <td>
                                    <input type="text" name="entries[mon_thu][{{ $timeSlot }}][location]" class="form-control" value="{{ $schedule ? $schedule->location : '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Friday Table -->
            <div class="col-md-6">
                <h2 class="text-center">Friday</h2>
                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Time Slot</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($friTimeSlots as $timeSlot)
                            @php
                                $schedule = $friSchedules->where('time_slot', $timeSlot)->first();
                            @endphp
                            <tr>
                                <td class="bg-dark text-white">{{ $timeSlot }}</td>
                                <td>
                                    <input type="text" name="entries[fri][{{ $timeSlot }}][location]" class="form-control" value="{{ $schedule ? $schedule->location : '' }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Update Schedules</button>
            <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
