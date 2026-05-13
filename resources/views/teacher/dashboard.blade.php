@extends('layouts.app')
@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-primary mb-1">{{ $teacher->subjects->count() }}</h2>
            <p class="mb-0 text-muted small">Assigned Subjects</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-success mb-1">{{ $teacher->quizzes->count() }}</h2>
            <p class="mb-0 text-muted small">Total Quizzes</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-warning mb-1">{{ $teacher->assignments->count() }}</h2>
            <p class="mb-0 text-muted small">Total Assignments</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom"><strong><i class="bi bi-book me-2 text-info"></i>My Subjects</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($teacher->subjects as $subject)
                    <li class="list-group-item">
                        {{ $subject->name }}
                        <small class="text-muted">({{ $subject->class->name ?? '' }})</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No subjects assigned yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom"><strong><i class="bi bi-journal-check me-2 text-primary"></i>Recent Quizzes</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($recent_quizzes as $quiz)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $quiz->title }}</span>
                        <small class="{{ $quiz->isExpired() ? 'text-danger' : 'text-success' }}">{{ $quiz->deadline->format('d M') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No quizzes yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom"><strong><i class="bi bi-file-earmark-text me-2 text-warning"></i>Recent Assignments</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($recent_assignments as $a)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $a->title }}</span>
                        <small class="{{ $a->isExpired() ? 'text-danger' : 'text-success' }}">{{ $a->deadline->format('d M') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No assignments yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
