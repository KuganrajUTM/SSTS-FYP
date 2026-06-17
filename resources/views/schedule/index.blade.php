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

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 184, 148, 0.02);
    }

    .time-slot-cell {
        background-color: var(--emerald-lt);
        color: #000000 !important;
        font-weight: 600;
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

    .btn-warning {
        font-family: 'Syne', sans-serif;
        font-weight: 600;
    }

    .btn-danger {
        font-family: 'Syne', sans-serif;
        font-weight: 600;
    }

    .alert-info {
        background-color: var(--emerald-lt);
        border: 1.5px solid var(--border);
        color: var(--emerald-dk);
        border-radius: 10px;
    }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="schedule-title">Driver's Schedule</h2>
    </div>

    <div class="row">
        <!-- Monday - Thursday Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i>Monday - Thursday
                </div>
                <div class="card-body p-0">
                    @if ($monThuSchedules->isEmpty())
                        <div class="alert alert-info text-center m-3">
                            No schedules available for Monday - Thursday.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Time Slot</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monThuSchedules as $schedule)
                                        <tr>
                                            <td class="time-slot-cell">{{ $schedule->time_slot }}</td>
                                            <td>{{ $schedule->location }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
                    @if ($friSchedules->isEmpty())
                        <div class="alert alert-info text-center m-3">
                            No schedules available for Friday.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Time Slot</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($friSchedules as $schedule)
                                        <tr>
                                            <td class="time-slot-cell">{{ $schedule->time_slot }}</td>
                                            <td>{{ $schedule->location }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
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
