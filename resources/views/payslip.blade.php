<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a2e; background: #fff; }

    .page { padding: 40px 50px; }

    .header { border-bottom: 3px solid #00b894; padding-bottom: 20px; margin-bottom: 24px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .company-name { font-size: 22px; font-weight: bold; color: #00b894; letter-spacing: 1px; }
    .company-sub { font-size: 10px; color: #666; margin-top: 3px; }
    .payslip-title { text-align: right; }
    .payslip-title h2 { font-size: 18px; color: #0a1628; font-weight: bold; }
    .payslip-title p { font-size: 10px; color: #666; margin-top: 4px; }

    .meta-row { display: flex; gap: 0; margin-bottom: 24px; }
    .meta-box { flex: 1; border: 1px solid #e0e0e0; padding: 14px 16px; }
    .meta-box:first-child { border-right: none; border-radius: 6px 0 0 6px; }
    .meta-box:last-child { border-radius: 0 6px 6px 0; }
    .meta-label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
    .meta-value { font-size: 13px; font-weight: bold; color: #0a1628; }

    .section-title { font-size: 11px; font-weight: bold; color: #00b894; text-transform: uppercase;
        letter-spacing: 0.8px; border-bottom: 1.5px solid #e0f7f3; padding-bottom: 6px; margin-bottom: 12px; }

    table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
    table th { background: #e6f9f5; color: #0a1628; font-size: 10px; font-weight: bold;
        text-transform: uppercase; letter-spacing: 0.5px; padding: 9px 12px; text-align: left; }
    table td { padding: 10px 12px; border-bottom: 1px solid #f0f0f0; font-size: 12px; }
    table tr:last-child td { border-bottom: none; }

    .total-row td { font-weight: bold; font-size: 14px; color: #00b894; background: #e6f9f5; }

    .bank-box { border: 1.5px solid #e0f7f3; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px; background: #f9fefe; }
    .bank-row { display: flex; gap: 40px; }
    .bank-item .label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .bank-item .value { font-size: 13px; font-weight: bold; color: #0a1628; }

    .status-paid { display: inline-block; background: #d1fae5; color: #065f46;
        padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }
    .status-pending { display: inline-block; background: #fef3c7; color: #92400e;
        padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; }

    .proof-note { font-size: 10px; color: #666; border-top: 1px solid #eee; padding-top: 14px; margin-top: 8px; }
    .footer { border-top: 2px solid #e0f7f3; margin-top: 30px; padding-top: 14px;
        display: flex; justify-content: space-between; font-size: 10px; color: #aaa; }
</style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div>
                <div class="company-name">SSTS</div>
                <div class="company-sub">School Student Transport System</div>
            </div>
            <div class="payslip-title">
                <h2>PAYSLIP</h2>
                <p>{{ \Carbon\Carbon::create($sal->year, $sal->month)->format('F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Driver meta --}}
    <div class="meta-row">
        <div class="meta-box">
            <div class="meta-label">Driver Name</div>
            <div class="meta-value">{{ $sal->driver->user->name ?? '-' }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-label">Pay Period</div>
            <div class="meta-value">{{ \Carbon\Carbon::create($sal->year, $sal->month)->format('F Y') }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-label">Payment Date</div>
            <div class="meta-value">{{ $sal->paid_at ? \Carbon\Carbon::parse($sal->paid_at)->format('d M Y') : '-' }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-label">Status</div>
            <div class="meta-value">
                @if($sal->status === 'Paid')
                    <span class="status-paid">Paid</span>
                @else
                    <span class="status-pending">Pending</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Earnings --}}
    <div class="section-title">Earnings</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align:right;">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monthly Salary — {{ \Carbon\Carbon::create($sal->year, $sal->month)->format('F Y') }}</td>
                <td style="text-align:right;">{{ number_format($sal->amount, 2) }}</td>
            </tr>
            @if($sal->notes)
            <tr>
                <td style="color:#666; font-size:11px; font-style:italic;">Note: {{ $sal->notes }}</td>
                <td></td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total Net Pay</td>
                <td style="text-align:right;">RM {{ number_format($sal->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Bank Details --}}
    <div class="section-title">Bank Transfer Details</div>
    <div class="bank-box">
        <div class="bank-row">
            <div class="bank-item">
                <div class="label">Bank Name</div>
                <div class="value">{{ $sal->driver->bank_name ?? 'Not provided' }}</div>
            </div>
            <div class="bank-item">
                <div class="label">Account Number</div>
                <div class="value">{{ $sal->driver->bank_account_number ?? 'Not provided' }}</div>
            </div>
        </div>
    </div>

    {{-- Proof note --}}
    @if($sal->receipt_pdf)
    <div class="proof-note">
        Transaction proof uploaded by admin. Download the proof separately from your salary records page.
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <span>Generated by SSTS &mdash; School Student Transport System</span>
        <span>{{ now()->format('d M Y, h:i A') }}</span>
    </div>

</div>
</body>
</html>
