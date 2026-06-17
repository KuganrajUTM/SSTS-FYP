@extends('layout.main-template')

@section('title', 'Driver Keys')
@section('content')
<style>
    :root {
      --emerald:    #00b894;
      --emerald-dk: #007a63;
      --emerald-lt: #e6f9f5;
      --navy:       #0a1628;
      --slate:      #4a5568;
      --white:      #ffffff;
      --border:     rgba(0,184,148,0.25);
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        color: var(--navy);
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transition: transform 0.2s;
    }

    .stat-card:hover { transform: translateY(-3px); }

    .stat-card.total      { background: linear-gradient(135deg, #6c7ae0 0%, #5563cc 100%); }
    .stat-card.avail      { background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%); }
    .stat-card.used-card  { background: linear-gradient(135deg, #636e72 0%, #2d3436 100%); }
    .stat-card.pending-card { background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); }

    .stat-card .card-body,
    .stat-card .card-footer { color: #fff; }

    .stat-card .card-footer {
        background: rgba(0,0,0,0.15);
        border-top: none;
        font-size: 1.4rem;
        font-weight: 700;
        font-family: 'Syne', sans-serif;
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

    .card-header.warning-header {
        background-color: #fff9e6 !important;
        border-bottom: 1.5px solid #f39c12;
        color: #7f6000;
    }

    .key-badge {
        font-family: 'Courier New', monospace;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.25em;
        color: var(--navy);
        background: var(--emerald-lt);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 0.2rem 0.7rem;
        display: inline-block;
    }

    .btn-generate {
        background: linear-gradient(135deg, var(--emerald) 0%, var(--emerald-dk) 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 10px;
        padding: 0.55rem 1.4rem;
        font-family: 'Syne', sans-serif;
        transition: opacity 0.2s;
    }

    .btn-generate:hover { opacity: 0.88; color: #fff; }

    .btn-send-key {
        background: linear-gradient(135deg, #f39c12 0%, #d68910 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.35rem 0.9rem;
        font-size: 0.82rem;
        font-family: 'Syne', sans-serif;
        transition: opacity 0.2s;
    }

    .btn-send-key:hover { opacity: 0.88; color: #fff; }

    .copy-btn {
        cursor: pointer;
        color: var(--emerald);
        background: none;
        border: none;
        font-size: 0.9rem;
        padding: 0;
        transition: color 0.2s;
    }

    .copy-btn:hover { color: var(--emerald-dk); }
</style>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title">
            <i class="fas fa-key me-2" style="color:var(--emerald);"></i> Driver Registration Keys
        </h2>
        <form method="POST" action="{{ route('admin.driver-keys.store') }}">
            @csrf
            <button type="submit" class="btn btn-generate">
                <i class="fas fa-plus-circle me-1"></i> Generate New Key
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card total">
                <div class="card-body">Total Keys</div>
                <div class="card-footer">{{ $totalCount }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card avail">
                <div class="card-body">Available</div>
                <div class="card-footer">{{ $availCount }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card used-card">
                <div class="card-body">Used</div>
                <div class="card-footer">{{ $usedCount }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stat-card pending-card">
                <div class="card-body">Pending Requests</div>
                <div class="card-footer">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>

    {{-- Pending Key Requests --}}
    <div class="card" style="border-color:#f39c12;">
        <div class="card-header warning-header">
            <i class="fas fa-inbox me-1"></i> Key Requests
            @if($pendingCount > 0)
                <span class="badge ms-2" style="background:#f39c12; color:#fff;">{{ $pendingCount }} pending</span>
            @endif
        </div>
        <div class="card-body">
            @if($requests->isEmpty())
                <p class="text-muted text-center py-3">No key requests yet.</p>
            @else
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#fff9e6; color:#7f6000; font-family:'Syne',sans-serif; font-size:0.85rem;">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Fulfilled</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $index => $req)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $req->name }}</strong></td>
                            <td>{{ $req->email }}</td>
                            <td>{{ $req->contact }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-success">Fulfilled</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $req->fulfilled_at ? $req->fulfilled_at->format('d M Y, H:i') : '—' }}</td>
                            <td style="text-align:center;">
                                @if($req->status === 'pending')
                                    <form method="POST" action="{{ route('admin.driver-keys.send', $req->id) }}"
                                          onsubmit="return confirm('Generate a key and send it to {{ $req->email }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-send-key">
                                            <i class="fas fa-paper-plane me-1"></i> Send Key
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Sent</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Keys Table --}}
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> All Generated Keys
        </div>
        <div class="card-body">
            @if($keys->isEmpty())
                <p class="text-muted text-center py-4">No keys generated yet. Click "Generate New Key" to create one.</p>
            @else
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:var(--emerald-lt); color:var(--emerald-dk); font-family:'Syne',sans-serif; font-size:0.85rem;">
                        <tr>
                            <th>No</th>
                            <th>Key Code</th>
                            <th>Status</th>
                            <th>Generated</th>
                            <th>Used At</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keys as $index => $key)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="key-badge" id="key-{{ $key->id }}">{{ $key->key_code }}</span>
                                @if(!$key->used)
                                    <button class="copy-btn ms-2" onclick="copyKey('{{ $key->key_code }}', this)"
                                            title="Copy key">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @endif
                            </td>
                            <td>
                                @if($key->used)
                                    <span class="badge bg-secondary">Used</span>
                                @else
                                    <span class="badge bg-success">Available</span>
                                @endif
                            </td>
                            <td>{{ $key->created_at->format('d M Y, H:i') }}</td>
                            <td>{{ $key->used_at ? $key->used_at->format('d M Y, H:i') : '—' }}</td>
                            <td style="text-align:center;">
                                @if(!$key->used)
                                    <form method="POST" action="{{ route('admin.driver-keys.destroy', $key->id) }}"
                                          onsubmit="return confirm('Delete key {{ $key->key_code }}? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script>
function copyKey(code, btn) {
    navigator.clipboard.writeText(code).then(() => {
        const icon = btn.querySelector('i');
        icon.className = 'fas fa-check';
        btn.style.color = '#27ae60';
        setTimeout(() => {
            icon.className = 'fas fa-copy';
            btn.style.color = '';
        }, 1500);
    });
}
</script>
@endsection
