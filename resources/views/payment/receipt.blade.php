<!-- resources/views/dashboard.blade.php -->
@extends('layout.main-template')

@section('content')

        <style>
            table#datatablesSimple th:nth-child(1), table#datatablesSimple td:nth-child(1) {
                width: 5%;
            }
            table#datatablesSimple th:nth-child(2), table#datatablesSimple td:nth-child(2) {
                width: 20%;
            }
            table#datatablesSimple th:nth-child(3), table#datatablesSimple td:nth-child(3) {
                width: 20%;
            }
            table#datatablesSimple th:nth-child(4), table#datatablesSimple td:nth-child(4) {
                width: 15%;
            }
            table#datatablesSimple th:nth-child(5), table#datatablesSimple td:nth-child(5) {
                width: 15%;
            }
            table#datatablesSimple th:nth-child(7), table#datatablesSimple td:nth-child(7) {

                text-align: center;
            }
        </style>
    <h1 class="mt-4">Receipt</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Receipt</li>
    </ol>

    <div class="d-flex justify-content-end mb-3">
        <form method="GET" action="" class="d-flex align-items-center gap-2" style="width: auto;">
    
            <label for="month" class="mb-0">Month:</label>
            <select name="month" class="form-select form-select-sm" id="month" onchange="this.form.submit()" size="1" style="width: auto; max-height: 120px; overflow-y: auto;">
                <option value="" {{ request('month') == '' ? 'selected' : '' }}>All Months</option>
                @foreach (range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                    </option>
                @endforeach
            </select>
    
            <!-- Year Dropdown -->
            <label for="year" class="mb-0">Year:</label>
            <select name="year" class="form-select form-select-sm" id="year" onchange="this.form.submit()" style="width: auto;">
                <option value="" {{ request('year') == '' ? 'selected' : '' }}>All Years</option>
                @foreach (range(now()->year, now()->year - 5) as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Receipt List
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Receipt NUmber</th>
                        <th>Child</th>
                        <th>Amount (RM)</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Receipt Number</th>
                        <th>Child</th>
                        <th>Amount (RM)</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>

                <tbody>
                    @foreach ($receipt as $rec)
                    <tr>
                        <td>1</td>
                        <td>{{ $rec->rec_num }}</td>
                        <td>{{ $rec->child->name }}</td>
                        <td>{{ number_format($rec->rec_amount,2) }}</td>
                        <td>{{ $rec->rec_date->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('view-receipt' , $rec->id) }}" class="btn btn-sm btn-primary view-btn">
                                <span><i class="fa-solid fa-eye"></i></span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            </div>

    </div>
@endsection
