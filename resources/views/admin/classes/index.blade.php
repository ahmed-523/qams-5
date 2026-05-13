@extends('layouts.app')
@section('title', 'Classes')
@section('page-title', 'Classes Management')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}" class="active"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">All Classes</h5>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm"
       data-bs-toggle="tooltip" title="Add a new class">
        <i class="bi bi-plus-lg me-1"></i>Add Class
    </a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Class Name</th>
                    <th class="d-none d-md-table-cell">Description</th>
                    <th>Subjects</th>
                    <th>Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $class->name }}</strong></td>
                    <td class="d-none d-md-table-cell">{{ $class->description ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $class->subjects_count }}</span></td>
                    <td><span class="badge bg-success">{{ $class->students_count }}</span></td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('admin.classes.edit', $class) }}"
                               class="btn btn-sm btn-outline-warning"
                               data-bs-toggle="tooltip" title="Edit Class">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.classes.destroy', $class) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Delete this class?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="tooltip" title="Delete Class">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No classes added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
