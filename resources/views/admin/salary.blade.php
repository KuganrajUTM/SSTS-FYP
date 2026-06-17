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

    .btn-pay {
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk));
        color: #fff; border: none; font-weight: 600;
        padding: 6px 18px; border-radius: 8px;
        font-size: 0.85rem; cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-pay:hover { opacity: 0.88; color: #fff; }

    .filter-btn {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        background: transparent;
        color: var(--navy);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .filter-btn:hover, .filter-btn.active {
        background: var(--emerald);
        color: #fff;
        border-color: var(--emerald);
    }

    table { font-size: 0.9rem; }
    thead th { background: var(--emerald-lt); color: var(--navy); font-weight: 700; border: none; }
    tbody td { vertical-align: middle; }
</style>

<div class="container my-5">

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-money-bill-wave me-2" style="color:var(--emerald);"></i> Driver Salary</h2>
        <button class="btn-pay" data-bs-toggle="modal" data-bs-target="#salaryModal">
            <i class="fas fa-plus me-1"></i> Pay Salary
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter bar --}}
    <div class="card-box">
        <form method="GET" action="{{ route('admin.salary') }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-600" style="font-size:0.85rem; color:var(--navy); font-weight:600;">Filter by Status</label>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.salary') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">All</a>
                    <a href="{{ route('admin.salary', ['status' => 'Paid']) }}" class="filter-btn {{ request('status') === 'Paid' ? 'active' : '' }}">Paid</a>
                    <a href="{{ route('admin.salary', ['status' => 'Pending']) }}" class="filter-btn {{ request('status') === 'Pending' ? 'active' : '' }}">Pending</a>
                </div>
            </div>
            <div class="col-auto ms-auto">
                <label class="form-label fw-600" style="font-size:0.85rem; color:var(--navy); font-weight:600;">Filter by Driver</label>
                <select name="driver_id" class="form-select form-select-sm" style="border:1.5px solid var(--border); border-radius:8px;" onchange="this.form.submit()">
                    <option value="">All Drivers</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Salary records table --}}
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
                        <th>Driver</th>
                        <th>Bank Name</th>
                        <th>Account No.</th>
                        <th>Amount (RM)</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaries as $i => $sal)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $sal->driver->user->name ?? '-' }}</strong></td>
                        <td>{{ $sal->driver->bank_name ?? '-' }}</td>
                        <td>{{ $sal->driver->bank_account_number ?? '-' }}</td>
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
                            <form action="{{ route('admin.salary.receipt', $sal->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label style="cursor:pointer; margin:0;" title="{{ $sal->receipt_pdf ? 'Replace receipt' : 'Upload receipt' }}">
                                    <input type="file" name="receipt" accept=".pdf" style="display:none;" onchange="this.form.submit()">
                                    <i class="fas fa-upload" style="font-size:1.1rem; color:{{ $sal->receipt_pdf ? 'var(--emerald)' : '#aaa' }};"></i>
                                </label>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- Pay Salary Modal --}}
<div class="modal fade" id="salaryModal" tabindex="-1" aria-labelledby="salaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:1.5px solid var(--border);">
            <div class="modal-header" style="background:var(--emerald-lt); border-bottom:1.5px solid var(--border);">
                <h5 class="modal-title" id="salaryModalLabel" style="font-family:'Syne',sans-serif; font-weight:700; color:var(--navy);">
                    <i class="fas fa-money-bill-wave me-2" style="color:var(--emerald);"></i> Pay Driver Salary
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.salary.store') }}">
                @csrf
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Driver <span class="text-danger">*</span></label>
                        <select name="driver_id" id="driverSelect" class="form-select" style="border:1.5px solid var(--border); border-radius:8px;" required onchange="showBankDetails(this)">
                            <option value="">Select Driver</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->id }}"
                                    data-bank="{{ $d->bank_name ?? '' }}"
                                    data-account="{{ $d->bank_account_number ?? '' }}">
                                    {{ $d->user->name }} ({{ $d->VRN }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="bankDetails" style="display:none; background:var(--emerald-lt); border:1.5px solid var(--border); border-radius:10px; padding:0.85rem 1rem; margin-bottom:1rem;">
                        <div style="font-weight:700; color:var(--navy); font-size:0.85rem; margin-bottom:0.5rem;">
                            <i class="fas fa-university me-1" style="color:var(--emerald);"></i> Bank Details
                        </div>
                        <div class="d-flex gap-4">
                            <div>
                                <div style="font-size:0.75rem; color:var(--slate); font-weight:600;">Bank Name</div>
                                <div id="bankName" style="font-weight:700; color:var(--navy); font-size:0.95rem;">—</div>
                            </div>
                            <div>
                                <div style="font-size:0.75rem; color:var(--slate); font-weight:600;">Account Number</div>
                                <div id="bankAccount" style="font-weight:700; color:var(--navy); font-size:0.95rem;">—</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Month <span class="text-danger">*</span></label>
                            <select name="month" class="form-select" style="border:1.5px solid var(--border); border-radius:8px;" required>
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Year <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" value="{{ now()->year }}"
                                   style="border:1.5px solid var(--border); border-radius:8px;" required min="2020">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Amount (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="0.01"
                               style="border:1.5px solid var(--border); border-radius:8px;" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" style="border:1.5px solid var(--border); border-radius:8px;" required>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-600" style="font-weight:600; color:var(--navy);">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional notes"
                               style="border:1.5px solid var(--border); border-radius:8px;">
                    </div>

                </div>
                <div class="modal-footer" style="border-top:1.5px solid var(--border);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-pay px-4">
                        <i class="fas fa-save me-1"></i> Save Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function showBankDetails(select) {
    const opt = select.options[select.selectedIndex];
    const bank = opt.dataset.bank;
    const account = opt.dataset.account;
    const box = document.getElementById('bankDetails');

    if (select.value && (bank || account)) {
        document.getElementById('bankName').textContent    = bank    || 'Not provided';
        document.getElementById('bankAccount').textContent = account || 'Not provided';
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
@endsection
