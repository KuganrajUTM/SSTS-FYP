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

    .schedule-title {
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

    .table thead th {
        color: var(--white);
    }

    .time-slot-cell {
        background-color: var(--emerald-lt);
        color: #000000 !important;
        font-weight: 600;
    }

    .form-control {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0, 184, 148, 0.1);
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
        <h2 class="schedule-title">Add Schedule</h2>
    </div>

    <form action="{{ route('Add_Schedule') }}" method="post">
        @csrf
        <div class="row">
            <!-- Monday - Thursday Table -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-calendar-alt me-2"></i>Monday - Thursday
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
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
                                            <td class="time-slot-cell">{{ $timeSlot }}</td>
                                            <td>
                                                <input type="text" name="entries[mon_thu][{{ $timeSlot }}][location]" class="form-control" placeholder="Enter Location">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Friday Table -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-calendar-day me-2"></i>Friday
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
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
                                            <td class="time-slot-cell">{{ $timeSlot }}</td>
                                            <td>
                                                <input type="text" name="entries[fri][{{ $timeSlot }}][location]" class="form-control" placeholder="Enter Location">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Add Schedule</button>
        </div>
    </form>
</div>
@endsection
