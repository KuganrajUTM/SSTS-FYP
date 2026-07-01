@extends('layout.main-template')

@section('content')

<h1 class="mt-4"><i class="fas fa-comment-dots me-2"></i>All Feedback & Complaints</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Feedback</li></ol>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="card-header"><i class="fas fa-list me-2"></i>Feedback Records</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="datatablesSimple">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>From</th>
                        <th>Type</th>
                        <th>About</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $i => $fb)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $fb->fromUser->name ?? '—' }}</td>
                        <td>
                            @if($fb->type === 'rating')
                                <span class="badge bg-warning text-dark">Rating</span>
                            @elseif($fb->type === 'feedback')
                                <span class="badge bg-success">Feedback</span>
                            @else
                                <span class="badge bg-danger">Complaint</span>
                            @endif
                        </td>
                        <td>
                            @if($fb->toDriver)
                                <i class="fas fa-car me-1 text-muted"></i>{{ $fb->toDriver->user->name ?? '—' }}
                            @elseif($fb->toChild)
                                <i class="fas fa-child me-1 text-muted"></i>{{ $fb->toChild->name ?? '—' }}
                            @else
                                <span class="badge bg-secondary">Management</span>
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
                        <td style="max-width:200px;">{{ $fb->comment }}</td>
                        <td>
                            @if($fb->status === 'reviewed')
                                <span class="badge bg-success">Reviewed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $fb->created_at->format('d M Y') }}</td>
                        <td>
                            @if($fb->status === 'pending')
                                <button class="btn btn-sm btn-primary" onclick="openReview({{ $fb->id }}, '{{ addslashes($fb->comment) }}')">
                                    <i class="fas fa-check me-1"></i>Review
                                </button>
                            @else
                                <span class="text-muted small">{{ $fb->manager_remark ?? 'No remark' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No feedback records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Review Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:1.5px solid rgba(0,184,148,0.25);">
            <div class="modal-header" style="background:#e6f9f5; border-bottom:1.5px solid rgba(0,184,148,0.25);">
                <h5 class="modal-title" style="font-weight:700; color:#0a1628;"><i class="fas fa-check-circle me-2" style="color:#00b894;"></i>Mark as Reviewed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted mb-3" style="font-size:0.88rem;" id="reviewFeedbackText"></p>
                    <label class="form-label fw-semibold">Manager Remark <span class="text-muted fw-normal">(optional)</span></label>
                    <textarea name="manager_remark" class="form-control" rows="3" placeholder="e.g. Driver has been verbally warned..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Mark as Reviewed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openReview(id, comment) {
    document.getElementById('reviewFeedbackText').textContent = '"' + comment + '"';
    document.getElementById('reviewForm').action = '/admin/feedback/' + id + '/review';
    new bootstrap.Modal(document.getElementById('reviewModal')).show();
}
</script>

@endsection
