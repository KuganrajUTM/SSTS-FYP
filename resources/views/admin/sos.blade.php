@extends('layout.main-template')

@section('content')
<style>
    :root {
        --emerald: #00b894; --emerald-dk: #007a63; --emerald-lt: #e6f9f5;
        --navy: #0a1628; --slate: #4a5568; --bg: #f5f7fa;
        --border: rgba(0,184,148,0.25); --danger: #e74c3c;
    }
    .sos-container { background: var(--bg); padding: 2rem 0; min-height: 100vh; }
    .sos-card { background: white; border-radius: 20px; border: 1.5px solid var(--border); box-shadow: 0 20px 60px rgba(0,184,148,0.15); margin: 2rem auto; max-width: 900px; }
    .sos-header { background: linear-gradient(135deg, var(--danger), #c0392b); color: white; padding: 1.5rem; border-radius: 16px 16px 0 0; font-weight: 700; font-size: 1.3rem; }
    .sos-item { border: 1.5px solid #fde8e8; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1rem; background: #fff8f8; }
    .sos-item:last-child { margin-bottom: 0; }
    .driver-badge { background: linear-gradient(135deg, var(--emerald), var(--emerald-dk)); color: white; border-radius: 20px; padding: 0.25rem 0.85rem; font-size: 0.82rem; font-weight: 700; display: inline-block; }
    .sos-time { color: var(--slate); font-size: 0.82rem; }
    .sos-transcript { background: #f8f9fa; border-left: 3px solid var(--danger); border-radius: 0 8px 8px 0; padding: 0.6rem 0.9rem; font-size: 0.88rem; color: #555; margin-top: 0.75rem; }
    .btn-delete { background: var(--danger); color: white; border: none; border-radius: 20px; padding: 0.35rem 1rem; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
    .btn-delete:hover { opacity: 0.85; }
    .no-data { text-align: center; color: var(--slate); font-style: italic; padding: 3rem; }
    audio { width: 100%; border-radius: 8px; margin-top: 0.75rem; }
</style>

<div class="sos-container">
    <div class="container">
        <h1 class="text-center mb-4" style="color: var(--navy); font-weight: 800; font-size: 2rem;">
            <i class="fas fa-exclamation-triangle me-2" style="color:var(--danger);"></i> SOS Inbox
        </h1>

        @if(session('success'))
            <div class="alert alert-success text-center" style="border-radius:10px;">{{ session('success') }}</div>
        @endif

        <div class="sos-card">
            <div class="sos-header">
                <i class="fas fa-headphones me-2"></i> Driver SOS Messages ({{ $messages->count() }})
            </div>
            <div class="p-4">
                @forelse($messages as $msg)
                    <div class="sos-item">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="driver-badge me-2">
                                    <i class="fas fa-user-tie me-1"></i>
                                    {{ $msg->driver->user->name ?? 'Unknown Driver' }}
                                </span>
                                <span class="sos-time">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $msg->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>
                            <form action="{{ route('admin.sos.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Delete this SOS message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete"><i class="fas fa-trash me-1"></i> Delete</button>
                            </form>
                        </div>

                        <audio controls>
                            <source src="{{ asset('sos-audio/' . $msg->audio_path) }}">
                            Your browser does not support audio playback.
                        </audio>

                        @if($msg->transcript)
                            <div class="sos-transcript">
                                <i class="fas fa-comment-dots me-1" style="color:var(--danger);"></i>
                                {{ $msg->transcript }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="no-data">
                        <i class="fas fa-check-circle" style="font-size:2rem; color:var(--emerald); display:block; margin-bottom:0.75rem;"></i>
                        No SOS messages received.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
