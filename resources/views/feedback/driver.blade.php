@extends('layout.main-template')

@section('content')

<h1 class="mt-4"><i class="fas fa-comment-dots me-2"></i>Feedback & Complaints</h1>
<ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Feedback</li></ol>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

{{-- Submit Feedback Form --}}
<div class="card mb-4">
    <div class="card-header"><i class="fas fa-pen me-2"></i>Submit Feedback or Complaint to Management</div>
    <div class="card-body">
        <form action="{{ route('feedback.driver.store') }}" method="POST">
            @csrf

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
                        <th>About</th>
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
                        <td><span class="badge bg-secondary">Management</span></td>
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
                <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#dfaq1">How do I submit feedback or a complaint to management?</button></h2>
                <div id="dfaq1" class="accordion-collapse collapse" data-bs-parent="#driverFaq">
                    <div class="accordion-body text-muted">Select either <strong>Feedback</strong> or <strong>Complaint</strong> as the type, write your message in the details field, and click Submit. Your submission will be sent directly to the manager.</div>
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
                    <div class="accordion-body text-muted">Your complaint is only visible to the manager. Other drivers and parents cannot view your submission.</div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
