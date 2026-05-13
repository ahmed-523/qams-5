@extends('layouts.app')
@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="form-card-wrap-sm">
    <div class="card">
        <div class="card-body p-4">
            <p class="text-muted small mb-4">
                <i class="bi bi-info-circle me-1"></i>
                You can only extend the deadline of an existing quiz. To change questions, delete and recreate.
            </p>
            <form action="{{ route('teacher.quizzes.update', $quiz) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Quiz Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $quiz->title }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Deadline *</label>
                    <input type="datetime-local" name="deadline" class="form-control" value="{{ $quiz->deadline->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Update Quiz
                    </button>
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
