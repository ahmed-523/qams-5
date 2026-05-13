@extends('layouts.app')
@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="active"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="form-card-wrap">
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.teachers.update', $teacher) }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->user->name) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $teacher->user->username) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">New Password <small class="text-muted">(leave blank to keep)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Education</label>
                        <input type="text" name="education" class="form-control" value="{{ old('education', $teacher->education) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Job History</label>
                        <textarea name="job_history" class="form-control" rows="3">{{ old('job_history', $teacher->job_history) }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Teacher</button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
