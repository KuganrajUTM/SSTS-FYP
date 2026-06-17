@extends('layout.main-template')

@section('content')

<h1 class="mt-4"><i class="fas fa-comment-dots me-2"></i>Complaints</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Complaints</li></ol>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Submit Complaint Form --}}
@if($children->isNotEmpty())
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-pen me-2"></i>Submit Complaint About a Child</div>
    <div class="card-body">
        <form action="{{ route('feedback.driver.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Child</label>
                <select name="to_child_id" class="form-select" required>
                    <option value="">-- Select Child --</option>
                    @foreach($children as $child)
                        <option value="{{ $child->id }}">{{ $child->name }} (Parent: {{ $child->parent->user->name ?? 'Unknown' }})</option>
                    @endforeach
                </select>
                @error('to_child_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Complaint Details</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Describe the issue..." required>{{ old('comment') }}</textarea>
                @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-danger"><i class="fas fa-paper-plane me-1"></i> Submit Complaint</button>
        </form>
    </div>
</div>
@endif

{{-- Complaint History --}}
<div class="card">
    <div class="card-header"><i class="fas fa-history me-2"></i>My Submitted Complaints</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Child</th>
                        <th>Parent</th>
                        <th>Complaint</th>
                        <th>Status</th>
                        <th>Manager Remark</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $i => $fb)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $fb->toChild->name ?? '—' }}</td>
                        <td>{{ $fb->toChild->parent->user->name ?? '—' }}</td>
                        <td>{{ $fb->comment }}</td>
                        <td>
                            @if($fb->status === 'reviewed')
                                <span class="badge bg-success">Reviewed</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $fb->manager_remark ?? '—' }}</td>
                        <td>{{ $fb->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No complaints submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
