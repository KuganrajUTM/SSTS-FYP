@extends('layout.main-template')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Add Schedule</h1>

    <form action="{{ route('Add_Schedule') }}" method="post">
        @csrf
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
                        @php
                            $monThuTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '1:45pm', '2:00pm','2:15pm', '2:30pm', '2:45pm'];
                        @endphp
                        @foreach ($monThuTimeSlots as $timeSlot)
                            <tr>
                                <td class="bg-dark text-white">{{ $timeSlot }}</td>
                                <td>
                                    <input type="text" name="entries[mon_thu][{{ $timeSlot }}][location]" class="form-control" placeholder="Enter Location">
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
                        @php
                            $friTimeSlots = ['6:00am', '6:15am', '6:30am', '6:45am', '7:00am', '7:15am', '12:30pm', '12:45pm', '1:00pm', '1:15pm', '1:30pm'];
                        @endphp
                        @foreach ($friTimeSlots as $timeSlot)
                            <tr>
                                <td class="bg-dark text-white">{{ $timeSlot }}</td>
                                <td>
                                    <input type="text" name="entries[fri][{{ $timeSlot }}][location]" class="form-control" placeholder="Enter Location">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Add Schedule</button>
        </div>
    </form>
</div>
@endsection
