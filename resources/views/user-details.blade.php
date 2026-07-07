@extends('layout.main-template')

@section('content')
<style>
    :root {
        --emerald: #00b894; --emerald-dk: #007a63; --emerald-lt: #e6f9f5;
        --navy: #0a1628; --slate: #4a5568; --white: #ffffff; --bg: #f5f7fa;
        --border: rgba(0,184,148,0.25);
    }
    .details-container { background: var(--bg); padding: 2rem 0; min-height: 100vh; }
    .details-card {
        background: var(--white); border-radius: 20px; border: 1.5px solid var(--border);
        box-shadow: 0 20px 60px rgba(0,184,148,0.15); max-width: 860px; margin: 2rem auto;
    }
    .details-header {
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk));
        color: var(--white); padding: 2.5rem 2rem; border-radius: 16px 16px 0 0; text-align: center;
    }
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; padding: 2.5rem 2.5rem 1rem; }
    .detail-item { display: flex; flex-direction: column; }
    .detail-label { font-weight: 700; color: var(--navy); margin-bottom: 0.5rem; font-size: 0.95rem; }
    .detail-value { color: var(--slate); font-size: 1.1rem; padding: 1rem; background: var(--bg); border-radius: 12px; border-left: 4px solid var(--emerald); }
    .btn-back {
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk));
        color: white; padding: 0.8rem 2rem; border-radius: 12px; text-decoration: none;
        font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin: 1rem 0;
    }
    .btn-back:hover { color: white; opacity: 0.9; }
    .status-badge { padding: 0.5rem 1rem; border-radius: 25px; font-weight: 600; font-size: 0.9rem; }
    .section-title {
        font-weight: 800; color: var(--navy); font-size: 1.1rem;
        padding: 0 2.5rem 1rem; border-bottom: 1.5px solid var(--border); margin-bottom: 0;
    }
    .child-card {
        border: 1.5px solid var(--border); border-radius: 14px;
        padding: 1.25rem 1.5rem; margin: 1rem 2.5rem;
        background: var(--bg);
    }
    .child-name { font-weight: 700; color: var(--navy); font-size: 1.05rem; margin-bottom: 0.5rem; }
    .child-meta { color: var(--slate); font-size: 0.9rem; margin-bottom: 1rem; }
    .assign-select {
        border: 1.5px solid var(--border); border-radius: 8px;
        padding: 0.5rem 0.75rem; width: 100%; color: var(--navy);
        background: white;
    }
    .assign-select:focus { outline: none; border-color: var(--emerald); box-shadow: 0 0 0 3px rgba(0,184,148,0.1); }
    .btn-assign {
        background: linear-gradient(135deg, var(--emerald), var(--emerald-dk));
        color: white; border: none; padding: 0.55rem 1.4rem;
        border-radius: 8px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;
    }
    .btn-assign:hover { opacity: 0.88; }
    .current-driver { font-size: 0.88rem; color: var(--emerald-dk); font-weight: 600; margin-top: 0.4rem; }
    .btn-recommend {
        background: linear-gradient(135deg, #6c5ce7, #a29bfe);
        color: white; border: none; padding: 0.55rem 1.4rem;
        border-radius: 8px; font-weight: 600; cursor: pointer; transition: opacity 0.2s;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-recommend:hover { opacity: 0.88; }
    .btn-recommend:disabled { opacity: 0.6; cursor: not-allowed; }
    .rec-box {
        background: #f0eeff; border: 1.5px solid #a29bfe;
        border-radius: 12px; padding: 1rem 1.25rem; margin-top: 0.75rem;
    }
    .rec-box.warn { background: #fff4e5; border-color: #fdcb6e; }
    .rec-box.error { background: #ffe8e8; border-color: #e17055; }
    .rec-title { font-weight: 700; color: #6c5ce7; margin-bottom: 0.6rem; font-size: 0.95rem; }
    .rec-driver { color: #2d3436; font-size: 0.9rem; padding: 0.4rem 0; border-bottom: 1px solid rgba(108,92,231,0.15); }
    .rec-driver:last-child { border-bottom: none; }
    @media (max-width: 768px) { .detail-grid { grid-template-columns: 1fr; gap: 1.5rem; } }
</style>

<div class="details-container">
    <div class="container">
        <a href="{{ route('admin.users') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="details-card">
            <div class="details-header">
                <h1 style="font-weight: 800; margin-bottom: 0.5rem;">{{ $user->name }}</h1>
                <div style="font-size: 1.1rem; opacity: 0.95;">
                    {{ $user->role === 'P' ? '👨‍👩‍👧‍👦 Parent' : '🚗 Driver' }}
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">📧 Email</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">👤 Username</div>
                    <div class="detail-value">{{ $user->username ?? 'N/A' }}</div>
                </div>

                @if($user->role === 'P' && $user->parent)
                <div class="detail-item">
                    <div class="detail-label">📍 Home Location</div>
                    <div class="detail-value">{{ $user->parent->location }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">🏙️ City</div>
                    <div class="detail-value">{{ $user->parent->city ?? 'Not set' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">🗺️ District</div>
                    <div class="detail-value">{{ $user->parent->district ?? 'Not set' }}</div>
                </div>
                @endif

                @if($user->role === 'D' && $user->driver)
                <div class="detail-item">
                    <div class="detail-label">🚙 Vehicle (VRN)</div>
                    <div class="detail-value">{{ $user->driver->VRN }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">🏙️ City</div>
                    <div class="detail-value">{{ $user->driver->city ?? 'Not set' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">🗺️ District</div>
                    <div class="detail-value">{{ $user->driver->district ?? 'Not set' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">✅ Verification Status</div>
                    <div class="detail-value">
                        @if($user->driver->verification)
                            <span class="status-badge {{ $user->driver->verification->ver_status === 'Approved' ? 'bg-success text-white' : ($user->driver->verification->ver_status === 'Rejected' ? 'bg-danger text-white' : 'bg-warning text-dark') }}">
                                {{ $user->driver->verification->ver_status }}
                            </span>
                            @if($user->driver->verification->rej_reason && $user->driver->verification->rej_reason !== 'N/A')
                                <div style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--slate);">
                                    Reason: {{ $user->driver->verification->rej_reason }}
                                </div>
                            @endif
                        @else
                            <span class="status-badge bg-secondary text-white">No Verification</span>
                        @endif
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">🏦 Bank Name</div>
                    <div class="detail-value">{{ $user->driver->bank_name ?? 'Not provided' }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">💳 Account Number</div>
                    <div class="detail-value">{{ $user->driver->bank_account_number ?? 'Not provided' }}</div>
                </div>
                @endif
            </div>

            {{-- Children + Driver Assignment (Parents only) --}}
            @if($user->role === 'P' && $user->parent)
                <div class="section-title mt-2">
                    👶 Children & Driver Assignment
                </div>

                {{-- AI Recommend --}}
                <div style="padding: 1rem 2.5rem 0;">
                    <button class="btn-recommend" id="recommendBtn" onclick="recommendDriver('{{ route('admin.recommend-driver', $user->parent->id) }}')"  >
                        <i class="fas fa-robot"></i> AI Recommend Driver
                    </button>
                    <div id="recommendResult"></div>
                </div>

                @forelse($user->parent->children as $child)
                    <div class="child-card">
                        <div class="child-name">{{ $child->name }}</div>
                        <div class="child-meta">
                            🏫 {{ $child->school_name ?? 'No school set' }}
                            @if($child->city || $child->district)
                                &nbsp;·&nbsp; 📍 {{ trim(($child->city ?? '') . ($child->city && $child->district ? ', ' : '') . ($child->district ?? '')) }}
                            @endif
                        </div>

                        @if($child->driver)
                            <div class="current-driver mb-2">
                                ✅ Current Driver: {{ $child->driver->user->name }}
                            </div>
                        @else
                            <div class="current-driver mb-2" style="color: #e17055;">
                                ⚠ No driver assigned
                            </div>
                        @endif

                        <form action="{{ route('admin.assign-driver', $child->id) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="driver_id" class="assign-select" required>
                                <option value="">-- Select Driver --</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ $child->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->user->name }} ({{ $driver->VRN }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-assign">Assign</button>
                        </form>
                    </div>
                @empty
                    <div style="padding: 1.5rem 2.5rem; color: var(--slate);">No children registered.</div>
                @endforelse

                <div style="padding-bottom: 1.5rem;"></div>
            @endif
        </div>
    </div>
</div>
{{-- No-children reminder (admin) --}}
@if($user->role === 'P' && $user->parent && $user->parent->children->isEmpty())
<div class="modal fade" id="noChildrenModal" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:1.5px solid rgba(0,184,148,0.25);">
            <div class="modal-header" style="background:#fff8e1;border-bottom:1.5px solid rgba(253,203,110,0.4);">
                <h5 class="modal-title" style="font-family:'Syne',sans-serif;font-weight:700;color:#b7791f;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Child Info Missing
                </h5>
            </div>
            <div class="modal-body p-4 text-center">
                <div style="width:64px;height:64px;background:#fff8e1;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;border:2px solid rgba(253,203,110,0.5);">
                    <i class="bi bi-person-fill-exclamation" style="font-size:1.8rem;color:#f6ad55;"></i>
                </div>
                <p style="font-size:0.95rem;color:#4a5568;margin:0;">
                    This parent has not added any child information yet.<br>
                    Please ask them to go to <strong>Edit Profile</strong> and add their child's details before a driver can be assigned.
                </p>
            </div>
            <div class="modal-footer" style="border-top:1.5px solid rgba(253,203,110,0.25);justify-content:center;">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                    style="background:linear-gradient(135deg,#f6ad55,#dd6b20);color:#fff;font-weight:700;border-radius:8px;font-family:'Syne',sans-serif;font-size:0.9rem;padding:0.45rem 1.5rem;">
                    <i class="bi bi-check2 me-1"></i> OK, Got It
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('noChildrenModal')).show();
    });
</script>
@endif

<script>
function recommendDriver(url) {
    const btn = document.getElementById('recommendBtn');
    const result = document.getElementById('recommendResult');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analyzing...';

    fetch(url)
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-robot"></i> AI Recommend Driver';

            if (data.status === 'error') {
                result.innerHTML = `<div class="rec-box error">⚠️ ${data.message}</div>`;
            } else if (data.status === 'none') {
                result.innerHTML = `<div class="rec-box warn">🔍 ${data.message}</div>`;
            } else {
                let html = `<div class="rec-box"><div class="rec-title">🤖 Recommended Drivers — ${data.location}</div>`;
                data.drivers.forEach(d => {
                    html += `<div class="rec-driver">✅ <strong>${d.name}</strong> (${d.vrn}) — <em>${d.city}, ${d.district}</em> — <strong>${d.passengers}</strong> passenger(s)</div>`;
                });
                html += `</div>`;
                result.innerHTML = html;
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-robot"></i> AI Recommend Driver';
            result.innerHTML = `<div class="rec-box error">❌ Failed to fetch recommendation. Try again.</div>`;
        });
}
</script>
@endsection
