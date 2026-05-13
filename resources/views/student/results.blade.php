@extends('layouts.app')
@section('title', 'My Results')
@section('page-title', 'My Results & Performance')

@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}" class="active"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection

@section('content')

@php
    $totalAttemptedCount = $quizAttempts->count();
    $publishedForAvg = $quizAttempts->filter(function ($attempt) {
        return $attempt->quiz && $attempt->quiz->is_result_published;
    });
    $avgQuiz = $publishedForAvg->count() > 0
        ? round($publishedForAvg->avg(function ($a) {
            return $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0;
        }), 1)
        : 0;
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-primary fw-bold mb-1">{{ $totalAttemptedCount }}</h2>
            <p class="mb-0 text-muted small">Quizzes Attempted</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-success fw-bold mb-1">{{ $avgQuiz }}%</h2>
            <p class="mb-0 text-muted small">Average Quiz Score</p>
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="card stat-card text-center p-3">
            <h2 class="text-warning fw-bold mb-1">{{ $avgAssignment }}</h2>
            <p class="mb-0 text-muted small">Avg Assignment Grade</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-white border-bottom py-3">
                <strong><i class="bi bi-journal-check me-2 text-primary"></i>Quiz Results</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Quiz</th>
                            <th class="d-none d-md-table-cell">Subject</th>
                            <th>Score</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quizAttempts as $a)
                            @php
                                $isPublished = $a->quiz && $a->quiz->is_result_published;
                                $pct = $a->total_marks > 0 ? round(($a->score / $a->total_marks) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td>{{ $a->quiz->title ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ $a->quiz->subject->name ?? '-' }}</td>
                                @if($isPublished)
                                    <td>{{ $a->score }}/{{ $a->total_marks }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pct >= 50 ? 'success' : 'danger' }}">{{ $pct }}%</span>
                                    </td>
                                @else
                                    <td colspan="2" class="text-center">
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-clock-history me-1"></i>Awaiting Result
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No quiz attempts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header bg-white border-bottom py-3">
                <strong><i class="bi bi-file-earmark-text me-2 text-warning"></i>Assignment Grades</strong>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Assignment</th>
                            <th class="d-none d-md-table-cell">Subject</th>
                            <th>Grade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $sub)
                            <tr>
                                <td>{{ $sub->assignment->title ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ $sub->assignment->subject->name ?? '-' }}</td>
                                <td>
                                    <span class="fw-bold {{ $sub->grade ? 'text-dark' : 'text-muted' }}">
                                        {{ $sub->grade ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($sub->is_zero_marked)
                                        <span class="badge bg-danger">Late/Zero</span>
                                    @elseif($sub->is_late)
                                        <span class="badge bg-warning text-dark">Late</span>
                                    @else
                                        <span class="badge bg-success">On Time</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
