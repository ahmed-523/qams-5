@extends('layouts.app')
@section('title', 'Quiz Results')
@section('page-title', 'Results: ' . $quiz->title)
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h6 class="text-muted mb-0">
        Total Marks: <strong>{{ $quiz->total_marks }}</strong>
        &nbsp;|&nbsp; Attempts: <strong>{{ $quiz->attempts->count() }}</strong>
    </h6>
    @if(!$quiz->is_result_published)
    <form action="{{ route('teacher.quizzes.publish', $quiz) }}" method="POST">
        @csrf
        <button class="btn btn-success btn-sm"
                onclick="return confirm('Publish results to students?')">
            <i class="bi bi-send me-1"></i>Publish Results
        </button>
    </form>
    @else
    <span class="badge bg-success fs-6">Results Published</span>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Score</th>
                    <th>Total</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quiz->attempts->sortByDesc('score') as $attempt)
                @php
                    $pct   = $attempt->total_marks > 0 ? round(($attempt->score/$attempt->total_marks)*100,1) : 0;
                    $grade = $pct>=80?'A':($pct>=65?'B':($pct>=50?'C':($pct>=40?'D':'F')));
                    $color = $pct>=80?'success':($pct>=65?'primary':($pct>=50?'warning':($pct>=40?'secondary':'danger')));
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $attempt->student->user->name ?? '-' }}</td>
                    <td>{{ $attempt->score }}</td>
                    <td>{{ $attempt->total_marks }}</td>
                    <td>{{ $pct }}%</td>
                    <td><span class="badge bg-{{ $color }}">{{ $grade }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No attempts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
