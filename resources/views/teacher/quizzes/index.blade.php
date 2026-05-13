@extends('layouts.app')
@section('title', 'My Quizzes')
@section('page-title', 'My Quizzes')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">All Quizzes</h5>
    <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary btn-sm"
       data-bs-toggle="tooltip" title="Create a new quiz">
        <i class="bi bi-plus-lg me-1"></i>Create Quiz
    </a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th class="d-none d-md-table-cell">Subject</th>
                    <th class="d-none d-lg-table-cell">Class</th>
                    <th class="d-none d-sm-table-cell">Marks</th>
                    <th>Deadline</th>
                    <th>Attempts</th>
                    <th class="d-none d-sm-table-cell">Results</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $quiz->title }}</strong>
                        <div class="d-md-none text-muted small">{{ $quiz->subject->name ?? '-' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $quiz->subject->name ?? '-' }}</td>
                    <td class="d-none d-lg-table-cell">{{ $quiz->class->name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $quiz->total_marks }}</td>
                    <td class="{{ $quiz->isExpired() ? 'text-danger' : 'text-success' }}" style="white-space:nowrap">
                        {{ $quiz->deadline->format('d M Y') }}
                    </td>
                    <td><span class="badge bg-info">{{ $quiz->attempts->count() }}</span></td>
                    <td class="d-none d-sm-table-cell">
                        @if($quiz->is_result_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                               class="btn btn-sm btn-outline-info"
                               data-bs-toggle="tooltip" title="View Quiz Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('teacher.quizzes.results', $quiz) }}"
                               class="btn btn-sm btn-outline-primary"
                               data-bs-toggle="tooltip" title="View Student Results">
                                <i class="bi bi-list-check"></i>
                            </a>
                            <a href="{{ route('teacher.quizzes.edit', $quiz) }}"
                               class="btn btn-sm btn-outline-warning"
                               data-bs-toggle="tooltip" title="Edit / Extend Deadline">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(!$quiz->is_result_published)
                            <form action="{{ route('teacher.quizzes.publish', $quiz) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-success"
                                        data-bs-toggle="tooltip" title="Publish Results to Students"
                                        onclick="return confirm('Publish results? Students will be able to see their scores.')">
                                    <i class="bi bi-send"></i>
                                </button>
                            </form>
                            @else
                                <span class="btn btn-sm btn-success disabled"
                                      data-bs-toggle="tooltip" title="Results already published">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">No quizzes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
