@extends('layouts.app')
@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}" class="active"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="form-card-wrap">
    <div class="card">
        <div class="card-body p-4">
            <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $student->user->name) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $student->user->username) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Admission Number *</label>
                        <input type="text" name="admission_number" class="form-control" value="{{ old('admission_number', $student->admission_number) }}" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Father's Name</label>
                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $student->father_name) }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select">
                            <option value="">-- No Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="picture" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update Student</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
