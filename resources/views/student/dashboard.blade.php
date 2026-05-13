@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('page-title', 'My Dashboard')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection

@section('content')

@php
    $pendingQuizzesCount = $all_quizzes
        ->filter(fn($quiz) => $quiz->deadline > now() && ! $attempted_quiz_ids->contains($quiz->id))
        ->count();

    $pendingAssignmentsCount = $upcoming_assignments
        ->filter(fn($a) => ! $submitted_assignment_ids->contains($a->id))
        ->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-primary fw-bold mb-1">{{ $pendingQuizzesCount }}</h2>
            <p class="mb-0 text-muted small">Pending Quizzes</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-warning fw-bold mb-1">{{ $pendingAssignmentsCount }}</h2>
            <p class="mb-0 text-muted small">Pending Assignments</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-success fw-bold mb-1" style="font-size:1.4rem">{{ $student->class->name ?? 'N/A' }}</h2>
            <p class="mb-0 text-muted small">My Class</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-journal-check me-2 text-primary"></i>All Quizzes</strong>
            </div>
            <div style="max-height:400px;overflow-y:auto;">
                <ul class="list-group list-group-flush">
                    @forelse($all_quizzes as $quiz)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-2 flex-wrap py-3">
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ $quiz->title }}</strong>
                                <small class="text-muted">{{ $quiz->subject->name ?? '' }}</small>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <small class="text-danger d-block">Due: {{ $quiz->deadline->format('d M') }}</small>
                                @if($attempted_quiz_ids->contains($quiz->id))
                                    <span class="badge bg-success">Attempted</span>
                                @elseif($quiz->deadline < now())
                                    <span class="badge bg-secondary">Expired</span>
                                @else
                                    <a href="{{ route('student.quizzes.attempt', $quiz) }}" class="btn btn-sm btn-primary">Attempt</a>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No quizzes available.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-file-earmark-text me-2 text-warning"></i>Upcoming Assignments</strong>
            </div>
            <div style="max-height:400px;overflow-y:auto;">
                <ul class="list-group list-group-flush">
                    @forelse($upcoming_assignments as $assignment)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-2 flex-wrap py-3">
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ $assignment->title }}</strong>
                                <small class="text-muted">{{ $assignment->subject->name ?? '' }}</small>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <small class="text-danger d-block">Due: {{ $assignment->deadline->format('d M') }}</small>
                                @if($submitted_assignment_ids->contains($assignment->id))
                                    <span class="badge bg-success">Submitted</span>
                                @else
                                    <a href="{{ route('student.assignments.show', $assignment) }}" class="btn btn-sm btn-warning">Submit</a>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No upcoming assignments.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
