@extends('layouts.app')
@section('title', 'Register Teacher')
@section('page-title', 'Register New Teacher')
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
            <form action="{{ route('admin.teachers.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Education</label>
                        <input type="text" name="education" class="form-control" value="{{ old('education') }}" placeholder="e.g. M.Sc Mathematics">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Job History</label>
                        <textarea name="job_history" class="form-control" rows="3" placeholder="Previous teaching experience...">{{ old('job_history') }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Register Teacher</button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
