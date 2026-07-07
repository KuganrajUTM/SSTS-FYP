@extends('layout.main-template')

@section('content')

<h1 class="mt-4"><i class="fas fa-comment-dots me-2"></i>Feedback & Complaints</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Feedback</li></ol>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Submit Feedback Form --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-pen me-2"></i>Submit Feedback or Complaint</div>
    <div class="card-body">
        <form action="{{ route('feedback.driver.store') }}" method="POST">
            @csrf

            {{-- Send To --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Send to</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="targetManagement" value="management" onchange="toggleDriverTarget('management')" checked>
                        <label class="form-check-label" for="targetManagement"><i class="fas fa-building me-1 text-secondary"></i>Management</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="targetParent" value="parent" onchange="toggleDriverTarget('parent')">
                        <label class="form-check-label" for="targetParent"><i class="fas fa-user-friends me-1 text-primary"></i>Parent</label>
                    </div>
                </div>
            </div>

            {{-- Parent Dropdown (shown only when target = parent) --}}
            <div class="mb-3" id="parentSection" style="display:none;">
                <label class="form-label fw-semibold">Select Parent</label>
                <select name="to_parent_id" class="form-select">
                    <option value="">-- Select Parent --</option>
                    @foreach($parents as $par)
                        <option value="{{ $par->id }}">{{ $par->user->name ?? 'Unknown' }}</option>
                    @endforeach
                </select>
                @error('to_parent_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Type --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Type</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeFeedback" value="feedback" checked>
                        <label class="form-check-label" for="typeFeedback"><i class="fas fa-thumbs-up text-success me-1"></i>Feedback</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" id="typeComplaint" value="complaint">
                        <label class="form-check-label" for="typeComplaint"><i class="fas fa-exclamation-circle text-danger me-1"></i>Complaint</label>
                    </div>
                </div>
                @error('type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Details</label>
                <textarea name="comment" class="form-control" rows="3" placeholder="Describe your feedback or complaint..." required>{{ old('comment') }}</textarea>
                @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Submit</button>
        </form>
    </div>
</div>

{{-- Submission History --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-history me-2"></i>My Submitted Feedback</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Sent To</th>
                        <th>Type</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Manager Remark</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $i => $fb)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            @if($fb->to_parent_id && $fb->toParent)
                                <span class="badge bg-primary"><i class="fas fa-user-friends me-1"></i>{{ $fb->toParent->user->name ?? 'Parent' }}</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-building me-1"></i>Management</span>
                            @endif
                        </td>
                        <td>
                            @if($fb->type === 'complaint')
                                <span class="badge bg-danger">Complaint</span>
                            @else
                                <span class="badge bg-success">Feedback</span>
                            @endif
                        </td>
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
                    <tr><td colspan="7" class="text-center text-muted py-4">No feedback submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- FAQ Section --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-question-circle me-2"></i>Frequently Asked Questions</div>
    <div class="card-body">
        <div class="accordion" id="driverFaq">

            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq1">Who can I send feedback to?</button></h2>
                <div id="dfaq1" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">You can send feedback or a complaint to <strong>Management</strong> for work-related concerns, or directly to a <strong>Parent</strong> if the matter involves one of your passengers' guardians.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq2">What types of issues can I report?</button></h2>
                <div id="dfaq2" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">You can report any work-related concerns such as scheduling issues, unfair treatment, route problems, system errors, or general suggestions for improvement.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq3">How long does it take for my feedback to be reviewed?</button></h2>
                <div id="dfaq3" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">The manager will review your submission as soon as possible. You can check the <strong>Status</strong> column in your history table to see when it has been reviewed.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq4">Can I see the manager's response to my feedback?</button></h2>
                <div id="dfaq4" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">Yes. Once reviewed, the manager's remark will appear in the <strong>Manager Remark</strong> column of your submission history.</div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq5">Is my complaint kept confidential?</button></h2>
                <div id="dfaq5" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">Feedback sent to Management is only visible to the manager. Feedback sent directly to a Parent is visible to that parent only.</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleDriverTarget(target) {
    document.getElementById('parentSection').style.display = target === 'parent' ? '' : 'none';
}
</script>

@endsection
