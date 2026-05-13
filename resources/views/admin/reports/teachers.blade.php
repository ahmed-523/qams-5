@extends('layouts.app')
@section('title', 'Teacher Reports')
@section('page-title', 'Teacher Reports')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}" class="active"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Teacher</th>
                    <th class="d-none d-md-table-cell">Username</th>
                    <th>Subjects</th>
                    <th>Quizzes</th>
                    <th>Assignments</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td><strong>{{ $teacher->user->name }}</strong></td>
                    <td class="d-none d-md-table-cell"><code>{{ $teacher->user->username }}</code></td>
                    <td>{{ $teacher->subjects->count() }}</td>
                    <td><span class="badge bg-primary">{{ $teacher->quizzes->count() }}</span></td>
                    <td><span class="badge bg-warning text-dark">{{ $teacher->assignments->count() }}</span></td>
                    <td>
                        @if($teacher->user->is_blocked)
                            <span class="badge bg-danger">Blocked</span>
                        @else
                            <span class="badge bg-success">Active</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No teachers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
