@extends('layouts.app')
@section('title', 'Students')
@section('page-title', 'Students Management')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}" class="active"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">All Students</h5>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm"
       data-bs-toggle="tooltip" title="Register a new student">
        <i class="bi bi-plus-lg me-1"></i>Register Student
    </a>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="d-none d-md-table-cell">Username</th>
                    <th class="d-none d-sm-table-cell">Admission No.</th>
                    <th class="d-none d-lg-table-cell">Father's Name</th>
                    <th class="d-none d-sm-table-cell">Class</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="fw-medium">{{ $student->user->name }}</span>
                        <div class="d-sm-none text-muted small">{{ $student->admission_number }} &bull; {{ $student->class->name ?? '-' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell"><code>{{ $student->user->username }}</code></td>
                    <td class="d-none d-sm-table-cell">{{ $student->admission_number }}</td>
                    <td class="d-none d-lg-table-cell">{{ $student->father_name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $student->class->name ?? '-' }}</td>
                    <td>
                        @if($student->user->is_blocked)
                            <span class="badge bg-danger">Blocked</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('admin.students.show', $student) }}"
                               class="btn btn-sm btn-outline-info"
                               data-bs-toggle="tooltip" title="View Student Profile">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}"
                               class="btn btn-sm btn-outline-warning"
                               data-bs-toggle="tooltip" title="Edit Student Info">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.students.block', $student) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $student->user->is_blocked ? 'btn-outline-success' : 'btn-outline-danger' }}"
                                        data-bs-toggle="tooltip"
                                        title="{{ $student->user->is_blocked ? 'Unblock this student account' : 'Block this student account' }}">
                                    {{ $student->user->is_blocked ? 'Unblock' : 'Block' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No students registered yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
