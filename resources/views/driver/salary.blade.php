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

    .card-box {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 15px;
        padding: 24px;
        box-shadow: 0 4px 16px rgba(0,184,148,0.07);
        margin-bottom: 28px;
    }

    .section-title {
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        color: var(--navy);
        font-size: 1.1rem;
        margin-bottom: 16px;
        border-left: 4px solid var(--emerald);
        padding-left: 10px;
    }

    .badge-paid {
        background: #d1fae5; color: #065f46;
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
    }

    .badge-pending {
        background: #fef3c7; color: #92400e;
        padding: 4px 12px; border-radius: 20px;
        font-size: 0.78rem; font-weight: 600;
    }

    table { font-size: 0.9rem; }
    thead th { background: var(--emerald-lt); color: var(--navy); font-weight: 700; border: none; }
    tbody td { vertical-align: middle; }
</style>

<div class="container my-5">

    <div class="d-flex align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-money-bill-wave me-2" style="color:var(--emerald);"></i> My Salary</h2>
    </div>

    <div class="card-box">
        <div class="section-title">Salary Records</div>
        @if($salaries->isEmpty())
            <p class="text-muted" style="font-size:0.9rem;">No salary records found.</p>
        @else
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Month / Year</th>
                        <th>Amount (RM)</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Payment Proof</th>
                        <th>Payslip</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaries as $i => $sal)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::create($sal->year, $sal->month)->format('F Y') }}</td>
                        <td>RM {{ number_format($sal->amount, 2) }}</td>
                        <td>
                            @if($sal->status === 'Paid')
                                <span class="badge-paid">Paid</span>
                            @else
                                <span class="badge-pending">Pending</span>
                            @endif
                        </td>
                        <td>{{ $sal->paid_at ? \Carbon\Carbon::parse($sal->paid_at)->format('d M Y') : '-' }}</td>
                        <td>
                            @if($sal->receipt_pdf)
                                <a href="{{ asset('salary-receipts/' . $sal->receipt_pdf) }}" target="_blank"
                                   style="background:linear-gradient(135deg,#0a1628,#1e3a5f); color:#fff; border-radius:20px; padding:4px 14px; font-size:0.8rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                    <i class="fas fa-file-alt"></i> View Proof
                                </a>
                            @else
                                <span class="text-muted" style="font-size:0.85rem;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($sal->status === 'Paid')
                                <a href="{{ route('driver.salary.payslip', $sal->id) }}"
                                   style="background:linear-gradient(135deg,var(--emerald),var(--emerald-dk)); color:#fff; border-radius:20px; padding:4px 14px; font-size:0.8rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                    <i class="fas fa-file-invoice"></i> Download
                                </a>
                            @else
                                <span class="text-muted" style="font-size:0.85rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
@endsection
