@extends('layout.main-template')

@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --white:      #ffffff;
      --border:     rgba(0,184,148,0.25);
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
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

    .form-select, .form-select-sm {
        border: 1.5px solid var(--border);
        border-radius: 8px;
    }

    .form-select:focus, .form-select-sm:focus {
        border-color: var(--emerald);
        box-shadow: 0 0 0 3px rgba(0,184,148,0.1);
    }

    table#datatablesSimple th:nth-child(1), table#datatablesSimple td:nth-child(1) { width: 5%; }
    table#datatablesSimple th:nth-child(2), table#datatablesSimple td:nth-child(2) { width: 20%; }
    table#datatablesSimple th:nth-child(3), table#datatablesSimple td:nth-child(3) { width: 20%; }
    table#datatablesSimple th:nth-child(4), table#datatablesSimple td:nth-child(4) { width: 15%; }
    table#datatablesSimple th:nth-child(5), table#datatablesSimple td:nth-child(5) { width: 15%; }
    table#datatablesSimple th:nth-child(7), table#datatablesSimple td:nth-child(7) { text-align: center; }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">Receipt</h2>
    </div>

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
            <i class="fas fa-table me-1"></i> Receipt List
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Receipt Number</th>
                        <th>Child</th>
                        <th>Amount (RM)</th>
                        <th>Method</th>
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
                        <th>Method</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($receipt as $rec)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $rec->rec_num }}</td>
                        <td>{{ $rec->child->name }}</td>
                        <td>{{ number_format($rec->rec_amount, 2) }}</td>
                        <td>{{ $rec->payment_method ?? 'Card' }}</td>
                        <td>{{ $rec->rec_date->format('d-m-Y') }}</td>
                        <td class="d-flex gap-1">
                            @if($rec->payment_method === 'QR Pay')
                                @if($rec->proof_path)
                                    <a href="{{ asset('storage/' . $rec->proof_path) }}" target="_blank" download class="btn btn-sm btn-primary" title="Download Proof">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('view-receipt', $rec->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
