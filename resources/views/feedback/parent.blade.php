@extends('layout.main-template')

@section('content')
<style>
    .star-btn { background:none; border:none; font-size:1.6rem; color:#ccc; cursor:pointer; padding:0 2px; transition:color 0.15s; }
    .star-btn.active, .star-btn:hover { color:#f39c12; }
    .badge-pending  { background:#f39c12; }
    .badge-reviewed { background:#00b894; }
</style>

<h1 class="mt-4"><i class="fas fa-comment-dots me-2"></i>Driver Feedback</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Feedback</li></ol>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Submit Feedback Form --}}
@if($drivers->isNotEmpty())
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-pen me-2"></i>Submit Feedback</div>
    <div class="card-body">
        <form action="{{ route('feedback.parent.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Driver</label>
                <select name="to_driver_id" class="form-select" required>
                    <option value="">-- Select Driver --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->user->name ?? 'Unknown' }} ({{ $driver->VRN }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Feedback Type</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeRating" value="rating" onchange="toggleRating(true)" checked>
                        <label class="form-check-label" for="typeRating"><i class="fas fa-star text-warning me-1"></i>Rating</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeComplaint" value="complaint" onchange="toggleRating(false)">
                        <label class="form-check-label" for="typeComplaint"><i class="fas fa-exclamation-circle text-danger me-1"></i>Complaint</label>
                    </div>
                </div>
            </div>

            <div class="mb-3" id="ratingSection">
                <label class="form-label fw-semibold">Star Rating</label><br>
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" class="star-btn" data-val="{{ $i }}" onclick="setStar({{ $i }})">&#9733;</button>
                @endfor
                <input type="hidden" name="rating" id="ratingInput" value="">
                @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Comment</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Write your feedback here..." required>{{ old('comment') }}</textarea>
                @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Submit Feedback</button>
        </form>
    </div>
</div>
@endif

{{-- Feedback History --}}
<div class="card">
    <div class="card-header"><i class="fas fa-history me-2"></i>My Submitted Feedback</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Driver</th>
                        <th>Type</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Manager Remark</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $i => $fb)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $fb->toDriver->user->name ?? '—' }}</td>
                        <td>
                            @if($fb->type === 'rating')
                                <span class="badge bg-warning text-dark">Rating</span>
                            @else
                                <span class="badge bg-danger">Complaint</span>
                            @endif
                        </td>
                        <td>
                            @if($fb->rating)
                                @for($s = 1; $s <= 5; $s++)
                                    <span style="color:{{ $s <= $fb->rating ? '#f39c12' : '#ccc' }}">&#9733;</span>
                                @endfor
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $fb->comment }}</td>
                        <td><span class="badge badge-{{ $fb->status }}">{{ ucfirst($fb->status) }}</span></td>
                        <td>{{ $fb->manager_remark ?? '—' }}</td>
                        <td>{{ $fb->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No feedback submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleRating(show) {
    document.getElementById('ratingSection').style.display = show ? '' : 'none';
    if (!show) document.getElementById('ratingInput').value = '';
}

function setStar(val) {
    document.getElementById('ratingInput').value = val;
    document.querySelectorAll('.star-btn').forEach(function(btn) {
        btn.classList.toggle('active', parseInt(btn.dataset.val) <= val);
    });
}
</script>
@endsection
