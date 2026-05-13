@extends('layouts.app')
@section('title', 'Quiz Details')
@section('page-title', $quiz->title)
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <p class="mb-2"><strong>Subject:</strong> {{ $quiz->subject->name ?? '-' }}</p>
                <p class="mb-2"><strong>Class:</strong> {{ $quiz->class->name ?? '-' }}</p>
                <p class="mb-0"><strong>Total Marks:</strong> {{ $quiz->total_marks }}</p>
            </div>
            <div class="col-12 col-md-6">
                <p class="mb-2"><strong>Deadline:</strong>
                    <span class="{{ $quiz->isExpired() ? 'text-danger' : 'text-success' }}">
                        {{ $quiz->deadline->format('d M Y, h:i A') }}
                    </span>
                </p>
                <p class="mb-2"><strong>Results:</strong>
                    @if($quiz->is_result_published)
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Hidden</span>
                    @endif
                </p>
                <p class="mb-0"><strong>Attempts:</strong> {{ $quiz->attempts->count() }}</p>
            </div>
        </div>
        <div class="mt-3 d-flex flex-wrap gap-2">
            <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-list-check me-1"></i>View Results
            </a>
            <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil me-1"></i>Edit
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-bottom">
        <strong><i class="bi bi-question-circle me-2 text-primary"></i>Questions ({{ $quiz->questions->count() }})</strong>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th class="d-none d-md-table-cell">Type</th>
                    <th class="d-none d-md-table-cell">Correct Answer</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quiz->questions as $q)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $q->question_text }}</td>
                    <td class="d-none d-md-table-cell"><span class="badge bg-secondary">{{ strtoupper($q->question_type) }}</span></td>
                    <td class="d-none d-md-table-cell"><code>{{ $q->correct_answer }}</code></td>
                    <td>{{ $q->marks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
