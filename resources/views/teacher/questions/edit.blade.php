@extends('layouts.app')
@section('title', 'Edit Question')
@section('page-title', 'Edit Question')

@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}" class="active"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="form-card-wrap">
    <div class="card">
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teacher.questions.update', $question) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="question_type" value="{{ $question->question_type }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Question Text *</label>
                    <textarea name="question_text" class="form-control" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                @if($question->question_type === 'mcq')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Options</label>
                    @foreach($question->options ?? [] as $i => $option)
                    <input type="text" name="options[]" class="form-control mb-2"
                           value="{{ old('options.'.$i, $option) }}" required>
                    @endforeach
                </div>
                @endif

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Correct Answer *</label>
                        <input type="text" name="correct_answer" class="form-control" required
                               value="{{ old('correct_answer', $question->correct_answer) }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Marks *</label>
                        <input type="number" name="marks" class="form-control" min="1"
                               value="{{ old('marks', $question->marks) }}" required>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Update Question
                    </button>
                    <a href="{{ route('teacher.questions.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
