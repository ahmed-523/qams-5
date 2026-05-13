@extends('layouts.app')
@section('title', 'Assignment Details')
@section('page-title', $assignment->title)
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <p class="mb-2"><strong>Subject:</strong> {{ $assignment->subject->name ?? '-' }}</p>
                <p class="mb-2"><strong>Class:</strong> {{ $assignment->class->name ?? '-' }}</p>
                <p class="mb-0"><strong>Total Marks:</strong> {{ $assignment->total_marks }}</p>
            </div>
            <div class="col-12 col-md-6">
                <p class="mb-2"><strong>Deadline:</strong>
                    <span class="{{ $assignment->isExpired() ? 'text-danger' : 'text-success' }}">
                        {{ $assignment->deadline->format('d M Y, h:i A') }}
                    </span>
                </p>
                <p class="mb-0"><strong>Submissions:</strong> {{ $assignment->submissions->count() }}</p>
            </div>
        </div>

        @if($assignment->description)
        <div class="mt-3">
            <strong>Instructions:</strong>
            <div class="border rounded p-2 bg-light mt-1">{{ $assignment->description }}</div>
        </div>
        @endif

        @if($assignment->document_path)
        <div class="alert alert-info d-flex align-items-center gap-3 mb-0 mt-3 flex-wrap">
            <i class="bi bi-file-earmark-word fs-3 flex-shrink-0"></i>
            <div>
                <strong>Assignment Document attached</strong><br>
                <a href="{{ asset('storage/' . $assignment->document_path) }}" target="_blank"
                   class="btn btn-sm btn-outline-primary mt-1">
                    <i class="bi bi-download me-1"></i>Download Document
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="d-flex flex-wrap gap-2">
    <a href="{{ route('teacher.assignments.submissions', $assignment) }}" class="btn btn-primary">
        <i class="bi bi-list-check me-1"></i>View Submissions
    </a>
    <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn btn-outline-warning">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
</div>
@endsection
