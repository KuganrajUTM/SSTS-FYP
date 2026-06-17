@extends('layout.main-template')

@section('content')
<style>
    .fpx-wrapper { max-width: 660px; margin: 0 auto; }

    .fpx-header {
        background: #1a3a5c;
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px 10px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fpx-header-title { font-size: 1.1rem; font-weight: 700; }
    .fpx-header-sub   { font-size: 0.75rem; opacity: 0.75; }

    .fpx-badge {
        background: #f7941d;
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 4px;
        letter-spacing: 1px;
    }

    .fpx-body {
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 10px 10px;
        padding: 1.75rem;
    }

    .txn-box {
        background: #f4f6f9;
        border: 1px solid #dde3ec;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.75rem;
        font-size: 0.875rem;
    }
    .txn-box table { width: 100%; border-collapse: collapse; }
    .txn-box td    { padding: 4px 0; vertical-align: top; }
    .txn-box td:first-child { color: #6c757d; width: 42%; }
    .txn-box td:last-child  { font-weight: 600; color: #1a3a5c; }
    .txn-amount { font-size: 1.35rem; font-weight: 700; color: #f7941d; }

    .section-label {
        font-weight: 700;
        color: #1a3a5c;
        font-size: 0.9rem;
        border-bottom: 2px solid #f7941d;
        padding-bottom: 6px;
        margin-bottom: 1.1rem;
    }

    .bank-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 0.5rem;
    }

    .bank-btn {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px 6px;
        cursor: pointer;
        text-align: center;
        background: white;
        transition: border-color 0.15s, background 0.15s;
        font-size: 0.72rem;
        font-weight: 600;
        color: #1a3a5c;
        user-select: none;
    }
    .bank-btn:hover    { border-color: #f7941d; background: #fff8f0; }
    .bank-btn.selected { border-color: #f7941d; background: #fff3e0; }

    .bank-icon {
        width: 42px; height: 42px;
        border-radius: 8px;
        margin: 0 auto 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.62rem;
        font-weight: 800;
        color: white;
    }

    .auth-section { display: none; }
    .auth-section.show { display: block; }

    .btn-change-bank {
        background: none;
        border: 1px solid #ccc;
        color: #555;
        padding: 6px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.82rem;
        margin-bottom: 1rem;
    }
    .btn-change-bank:hover { background: #f5f5f5; }

    .selected-bank-bar {
        background: #eef2f7;
        border: 1px solid #dde3ec;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a3a5c;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-authorize {
        background: linear-gradient(135deg, #f7941d, #d97b0c);
        border: none;
        color: white;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-authorize:hover { opacity: 0.9; }

    .security-note {
        font-size: 0.72rem;
        color: #999;
        text-align: center;
        margin-top: 1rem;
    }

    .fpx-footer {
        text-align: center;
        margin-top: 12px;
        font-size: 0.72rem;
        color: #aaa;
    }

    .tac-note { font-size: 0.78rem; color: #888; margin-top: 4px; }

    .form-control:focus {
        border-color: #f7941d;
        box-shadow: 0 0 0 3px rgba(247,148,29,0.15);
    }
</style>

<div class="container my-5">
    <div class="fpx-wrapper">

        {{-- Header --}}
        <div class="fpx-header">
            <div>
                <div class="fpx-header-title">FPX Payment Gateway</div>
                <div class="fpx-header-sub">Secured by PayNet</div>
            </div>
            <span class="fpx-badge">FPX</span>
        </div>

        <div class="fpx-body">

            {{-- Transaction Summary --}}
            <div class="txn-box">
                <table>
                    <tr>
                        <td>Merchant</td>
                        <td>SSTS SDN BHD</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>SSTS Transportation Fee</td>
                    </tr>
                    <tr>
                        <td>Reference No.</td>
                        <td>SSTS-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr>
                        <td>Date &amp; Time</td>
                        <td>{{ now()->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td class="txn-amount">RM {{ number_format($payment->pay_amount, 2) }}</td>
                    </tr>
                </table>
            </div>

            {{-- Step 1: Bank Selection --}}
            <div id="bank-selection">
                <div class="section-label">Select Your Bank</div>
                <div class="bank-grid">
                    @php
                        $banks = [
                            ['name' => 'Maybank2U',    'short' => 'MBB',  'bg' => '#FFDE00', 'fg' => '#000000'],
                            ['name' => 'CIMB Clicks',  'short' => 'CIMB', 'bg' => '#c0392b', 'fg' => '#ffffff'],
                            ['name' => 'Public Bank',  'short' => 'PBB',  'bg' => '#003087', 'fg' => '#ffffff'],
                            ['name' => 'RHB Now',      'short' => 'RHB',  'bg' => '#5b2d8e', 'fg' => '#ffffff'],
                            ['name' => 'Hong Leong',   'short' => 'HLB',  'bg' => '#007a4d', 'fg' => '#ffffff'],
                            ['name' => 'AmOnline',     'short' => 'AMB',  'bg' => '#e65c00', 'fg' => '#ffffff'],
                            ['name' => 'Bank Islam',   'short' => 'BIMB', 'bg' => '#006633', 'fg' => '#ffffff'],
                            ['name' => 'Bank Rakyat',  'short' => 'BRB',  'bg' => '#1a5276', 'fg' => '#ffffff'],
                            ['name' => 'Affin Bank',   'short' => 'ABB',  'bg' => '#1b2a6b', 'fg' => '#ffffff'],
                            ['name' => 'Alliance Bank','short' => 'ABMB', 'bg' => '#c0392b', 'fg' => '#ffffff'],
                            ['name' => 'BSN',          'short' => 'BSN',  'bg' => '#0056a2', 'fg' => '#ffffff'],
                            ['name' => 'Bank Muamalat','short' => 'BMB',  'bg' => '#2c3e50', 'fg' => '#ffffff'],
                        ];
                    @endphp

                    @foreach($banks as $bank)
                        <div class="bank-btn" onclick="selectBank(this, '{{ $bank['name'] }}')">
                            <div class="bank-icon" style="background:{{ $bank['bg'] }}; color:{{ $bank['fg'] }};">
                                {{ $bank['short'] }}
                            </div>
                            {{ $bank['name'] }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Hidden form submitted on bank selection --}}
            <form id="bank-form" method="POST" action="{{ route('fpx.process', $payment->id) }}" style="display:none;">
                @csrf
                <input type="hidden" name="bank" id="bank-input">
            </form>

        </div>

        <div class="fpx-footer">
            Powered by <strong>PayNet FPX</strong> &nbsp;|&nbsp; &copy; {{ date('Y') }} Payments Network Malaysia Sdn Bhd
        </div>
    </div>
</div>

<script>
    const bankColors = {
        @foreach($banks as $bank)
        '{{ $bank['name'] }}': { bg: '{{ $bank['bg'] }}', fg: '{{ $bank['fg'] }}', short: '{{ $bank['short'] }}' },
        @endforeach
    };

    function selectBank(el, name) {
        document.querySelectorAll('.bank-btn').forEach(b => b.classList.remove('selected'));
        el.classList.add('selected');

        document.getElementById('bank-input').value = name;
        document.getElementById('bank-form').submit();
    }
</script>
@endsection
