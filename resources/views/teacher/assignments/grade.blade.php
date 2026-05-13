@extends('layouts.app')
@section('title', 'Grade Submission')
@section('page-title', 'Grade Submission')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="row g-4">
    <div class="col-12 col-md-7">
        <div class="card mb-3">
            <div class="card-header bg-white border-bottom"><strong><i class="bi bi-person me-2"></i>Student's Answer</strong></div>
            <div class="card-body">
                <p class="mb-2"><strong>Student:</strong> {{ $submission->student->user->name ?? '-' }}</p>
                <hr>
                @if($submission->solution_text)
                    <div class="border rounded p-3 bg-light">{{ $submission->solution_text }}</div>
                @else
                    <p class="text-muted fst-italic">No text submitted.</p>
                @endif
                @if($submission->file_path)
                <a href="{{ route('download.submission', $submission) }}" target="_blank"
                   class="btn btn-sm btn-outline-secondary mt-2">
                    <i class="bi bi-download me-1"></i>Download File
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-header bg-white border-bottom"><strong><i class="bi bi-star me-2"></i>Assign Grade</strong></div>
            <div class="card-body">
                <form action="{{ route('teacher.assignments.grade', [$assignment, $submission]) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Grade (out of {{ $assignment->total_marks }}) *</label>
                        <input type="number" name="grade" class="form-control"
                               min="0" max="{{ $assignment->total_marks }}"
                               value="{{ old('grade', $submission->grade) }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Feedback</label>
                        <textarea name="feedback" class="form-control" rows="4"
                            placeholder="Optional feedback for the student...">{{ old('feedback', $submission->feedback) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-1"></i>Save Grade
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
