@extends('layouts.app')
@section('title', 'Edit Assignment')
@section('page-title', 'Edit Assignment')
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="form-card-wrap">
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('teacher.assignments.update', $assignment) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $assignment->title) }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Deadline *</label>
                        <input type="datetime-local" name="deadline" class="form-control" value="{{ $assignment->deadline->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Total Marks *</label>
                        <input type="number" name="total_marks" class="form-control" min="1" value="{{ old('total_marks', $assignment->total_marks) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Student Submission Type *</label>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="submission_type" id="type_text" value="text"
                                {{ old('submission_type', $assignment->submission_type) === 'text' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_text">
                                <i class="bi bi-pencil-square me-1"></i>Written Answer (Text Box)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="submission_type" id="type_file" value="file"
                                {{ old('submission_type', $assignment->submission_type) === 'file' ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_file">
                                <i class="bi bi-file-earmark-arrow-up me-1"></i>File Upload (Word/PDF)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Brief Instructions</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $assignment->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Replace Assignment Document</label>
                    @if($assignment->document_path)
                        <div class="alert alert-info py-2 mb-2 small">
                            <i class="bi bi-file-earmark-word me-1"></i>
                            Current document:
                            <a href="{{ route('download.assignment', $assignment) }}" class="fw-semibold">Download / View</a>
                            &nbsp;— Upload a new file below to replace it.
                        </div>
                    @endif
                    <input type="file" name="document" class="form-control" accept=".doc,.docx,.pdf">
                    <div class="form-text">Leave blank to keep the existing document. Accepted: .doc, .docx, .pdf — Max 10 MB.</div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Assignment</button>
                    <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
