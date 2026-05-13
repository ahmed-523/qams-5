@extends('layouts.app')
@section('title', 'Quiz Result')
@section('page-title', $quiz->is_result_published ? 'Result: ' . $quiz->title : 'Quiz Submitted')
@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection
@section('content')

@if(!$quiz->is_result_published)
    <div class="text-center py-5">
        <div class="mb-3" style="font-size:4rem;">⏳</div>
        <h4 class="fw-bold">Quiz Submitted Successfully!</h4>
        <p class="text-muted fs-5 mb-1">Your answers have been recorded.</p>
        <p class="text-muted">Results will be visible once your teacher publishes them.<br>Check back later.</p>
        <a href="{{ route('student.quizzes.index') }}" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left me-1"></i>Back to Quizzes
        </a>
    </div>

@else
    @php
        $pct   = $attempt->total_marks > 0 ? round(($attempt->score / $attempt->total_marks) * 100, 1) : 0;
        $grade = $pct >= 80 ? 'A' : ($pct >= 65 ? 'B' : ($pct >= 50 ? 'C' : ($pct >= 40 ? 'D' : 'F')));
        $color = $pct >= 80 ? 'success' : ($pct >= 65 ? 'primary' : ($pct >= 50 ? 'warning' : ($pct >= 40 ? 'secondary' : 'danger')));
    @endphp

    <div class="text-center mb-4">
        <div class="display-4 fw-bold text-{{ $color }}">{{ $pct }}%</div>
        <div class="fs-5 mt-1">
            Score: {{ $attempt->score }} / {{ $attempt->total_marks }}
            &nbsp; Grade: <span class="badge bg-{{ $color }} fs-6">{{ $grade }}</span>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white border-bottom"><strong>Answer Review</strong></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th class="d-none d-md-table-cell">Your Answer</th>
                        <th class="d-none d-md-table-cell">Correct Answer</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attempt->answers as $ans)
                    <tr class="{{ $ans->is_correct ? 'table-success' : 'table-danger' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ans->question->question_text ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $ans->answer }}</td>
                        <td class="d-none d-md-table-cell">{{ $ans->question->correct_answer ?? '-' }}</td>
                        <td>{{ $ans->marks_obtained }}/{{ $ans->question->marks ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('student.quizzes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Quizzes
        </a>
    </div>
@endif
@endsection
