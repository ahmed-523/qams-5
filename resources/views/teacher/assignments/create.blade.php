@extends('layouts.app')
@section('title', 'Create Assignment')
@section('page-title', 'Create Assignment')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="form-card-wrap">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom pt-4 pb-3">
            <h5 class="mb-0 text-primary"><i class="bi bi-plus-circle me-2"></i>Create New Assignment</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('teacher.assignments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Assignment Title *</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}"
                        placeholder="Enter assignment title...">
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject &amp; Class *</label>
                        <select name="subject_id" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }} ({{ $subject->class->name ?? 'No Class' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Deadline *</label>
                        <input type="datetime-local" name="deadline" class="form-control" required value="{{ old('deadline') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Total Marks *</label>
                        <input type="number" name="total_marks" class="form-control" min="1"
                            value="{{ old('total_marks', 100) }}" required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Student Submission Type *</label>
                    <div class="d-flex flex-wrap gap-4 p-3 bg-light rounded border">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="submission_type" id="type_text"
                                value="text" {{ old('submission_type', 'text') === 'text' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="type_text">
                                <i class="bi bi-pencil-square text-primary me-1"></i>Written Answer (Text Box)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="submission_type" id="type_file"
                                value="file" {{ old('submission_type') === 'file' ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="type_file">
                                <i class="bi bi-file-earmark-arrow-up text-danger me-1"></i>File Upload (Word/PDF)
                            </label>
                        </div>
                    </div>
                    <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Choose how students will submit their answers.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Brief Instructions</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Write any specific rules or short instructions for the students here...">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4 p-3 border rounded border-info bg-white">
                    <label class="form-label fw-semibold">Assignment Document <span class="text-muted fw-normal">(Word / PDF)</span></label>
                    <input type="file" name="document" class="form-control" accept=".doc,.docx,.pdf">
                    <div class="form-text mt-2">Upload a <strong>.doc</strong>, <strong>.docx</strong>, or <strong>.pdf</strong> file. Students can download it. Max size: 10 MB.</div>
                </div>

                <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                    <div>Students who submit after the deadline automatically receive <strong>0 marks</strong>.</div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Create Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
