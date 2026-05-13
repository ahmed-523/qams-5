@extends('layouts.app')
@section('title', 'Assignment')
@section('page-title', $assignment->title)
@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection
@section('content')

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <p class="mb-2"><strong>Subject:</strong> {{ $assignment->subject->name ?? '-' }}</p>
                <p class="mb-2"><strong>Total Marks:</strong> {{ $assignment->total_marks }}</p>
                <p class="mb-0">
                    <strong>Submission Type:</strong>
                    @if($assignment->isTextSubmission())
                        <span class="badge bg-info"><i class="bi bi-pencil-square me-1"></i>Written Answer</span>
                    @else
                        <span class="badge bg-primary"><i class="bi bi-file-earmark-arrow-up me-1"></i>File Upload</span>
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-6">
                <p class="mb-2">
                    <strong>Deadline:</strong>
                    <span class="{{ $assignment->isExpired() ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                        {{ $assignment->deadline->format('d M Y, h:i A') }}
                    </span>
                </p>
                @if($assignment->isExpired() && !$submission)
                    <div class="badge bg-danger">Deadline Passed — Late submission = 0 marks</div>
                @endif
            </div>
        </div>

        @if($assignment->description)
        <div class="mt-3">
            <strong>Instructions from Teacher:</strong>
            <div class="border rounded p-3 bg-light mt-1">{{ $assignment->description }}</div>
        </div>
        @endif

        @if($assignment->document_path)
        <div class="alert alert-primary d-flex align-items-center gap-3 flex-wrap mt-3 mb-0">
            <i class="bi bi-file-earmark-word fs-2 text-primary flex-shrink-0"></i>
            <div>
                <strong>Assignment Document</strong><br>
                <span class="text-muted small">Download to see the full assignment questions.</span><br>
                <a href="{{ route('download.assignment', $assignment) }}" class="btn btn-primary btn-sm mt-2">
                    <i class="bi bi-download me-1"></i>Download Assignment Document
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@if($submission)
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <strong>Your Submission</strong>
        @if($submission->is_zero_marked)
            <span class="badge bg-danger">Late — 0 Marks</span>
        @elseif($submission->grade !== null)
            <span class="badge bg-success">Graded: {{ $submission->grade }}/{{ $assignment->total_marks }}</span>
        @else
            <span class="badge bg-warning text-dark">Awaiting Grade</span>
        @endif
    </div>
    <div class="card-body">
        @if($submission->solution_text)
            <strong>Your Written Answer:</strong>
            <div class="border rounded p-3 bg-light mb-3">{{ $submission->solution_text }}</div>
        @endif

        @if($submission->file_path)
            <a href="{{ route('download.submission', $submission) }}" class="btn btn-sm btn-outline-secondary mb-3">
                <i class="bi bi-download me-1"></i>Download Your Submitted File
            </a>
        @endif

        @if(!$submission->solution_text && !$submission->file_path)
            <div class="text-muted mb-3">No submission content.</div>
        @endif

        @if($submission->feedback)
        <div class="alert alert-info mb-0">
            <strong><i class="bi bi-chat-left-text me-1"></i>Teacher Feedback:</strong><br>
            {{ $submission->feedback }}
        </div>
        @endif

        @if($submission->is_zero_marked)
        <div class="alert alert-danger mb-0 mt-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Your submission was received after the deadline. <strong>0 marks</strong> have been assigned automatically.
        </div>
        @endif
    </div>
</div>

@else
<div class="card">
    <div class="card-header bg-white"><strong>Submit Your Answer</strong></div>
    <div class="card-body">
        <form action="{{ route('student.assignments.submit', $assignment) }}" method="POST"
              enctype="multipart/form-data" id="assignment-form">
            @csrf

            @if($assignment->isTextSubmission())
                <div class="mb-3">
                    <label class="form-label fw-semibold">Your Written Answer *</label>
                    <textarea name="solution_text" class="form-control" rows="7" required
                        placeholder="Type your answer here...">{{ old('solution_text') }}</textarea>
                    @error('solution_text')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="mb-4">
                    <label class="form-label fw-semibold">Upload Your Answer File *</label>
                    <input type="file" name="file" class="form-control" required
                           accept=".doc,.docx,.pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Accepted: Word (.doc, .docx), PDF (.pdf), Image (.jpg, .png) — Max 10 MB</div>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <button type="button" class="btn btn-success" onclick="showSubmitModal()">
                <i class="bi bi-send me-1"></i>Submit Assignment
            </button>
        </form>
    </div>
</div>
@endif

{{-- Custom Submit Modal --}}
<div id="submit-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(3px);">
    <div style="background:white;border-radius:20px;padding:30px 24px;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="width:56px;height:56px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.6rem;">📤</div>
        <div style="font-size:1.1rem;font-weight:700;color:#1a202c;margin-bottom:8px;">Submit Assignment?</div>
        <div style="font-size:0.88rem;color:#6c757d;margin-bottom:22px;line-height:1.5;">You cannot change your answer after submission.</div>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <button onclick="closeSubmitModal()" style="padding:9px 22px;border-radius:10px;font-weight:600;font-size:0.9rem;border:1.5px solid #e2e8f0;background:#f8f9fa;color:#495057;cursor:pointer;">Cancel</button>
            <button onclick="confirmSubmit()" style="padding:9px 22px;border-radius:10px;font-weight:600;font-size:0.9rem;border:none;background:#198754;color:white;cursor:pointer;">Yes, Submit</button>
        </div>
    </div>
</div>

<script>
function showSubmitModal()  { document.getElementById('submit-overlay').style.display = 'flex'; }
function closeSubmitModal() { document.getElementById('submit-overlay').style.display = 'none'; }
function confirmSubmit()    { document.getElementById('assignment-form').submit(); }
</script>

@endsection
