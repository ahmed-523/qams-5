@extends('layouts.app')
@section('title', 'Question Bank')
@section('page-title', 'Question Bank')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}" class="active"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">My Questions</h5>
    <a href="{{ route('teacher.questions.create') }}" class="btn btn-primary btn-sm"
       data-bs-toggle="tooltip" title="Add a new question to the bank">
        <i class="bi bi-plus-lg me-1"></i>Add Question
    </a>
</div>
<div class="mb-3">
    <form method="GET" class="d-flex">
        <select name="subject_id" class="form-select" style="max-width:280px" onchange="this.form.submit()">
            <option value="">All Subjects</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                    {{ $subject->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Type</th>
                    <th class="d-none d-md-table-cell">Subject</th>
                    <th>Marks</th>
                    <th class="d-none d-lg-table-cell">Correct Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span>{{ Str::limit($q->question_text, 60) }}</span>
                        <div class="d-md-none text-muted small">{{ $q->subject->name ?? '-' }}</div>
                    </td>
                    <td><span class="badge bg-secondary">{{ strtoupper($q->question_type) }}</span></td>
                    <td class="d-none d-md-table-cell">{{ $q->subject->name ?? '-' }}</td>
                    <td>{{ $q->marks }}</td>
                    <td class="d-none d-lg-table-cell"><code>{{ $q->correct_answer }}</code></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('teacher.questions.edit', $q) }}"
                               class="btn btn-sm btn-outline-warning"
                               data-bs-toggle="tooltip" title="Edit Question">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('teacher.questions.destroy', $q) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Delete this question?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete Question">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No questions yet. Add your first question.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
