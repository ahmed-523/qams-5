@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-primary mb-1">{{ $stats['total_students'] }}</h2>
            <p class="mb-0 text-muted small">Students</p>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-success mb-1">{{ $stats['total_teachers'] }}</h2>
            <p class="mb-0 text-muted small">Teachers</p>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-info mb-1">{{ $stats['total_classes'] }}</h2>
            <p class="mb-0 text-muted small">Classes</p>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-warning mb-1">{{ $stats['total_subjects'] }}</h2>
            <p class="mb-0 text-muted small">Subjects</p>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-danger mb-1">{{ $stats['total_quizzes'] }}</h2>
            <p class="mb-0 text-muted small">Quizzes</p>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card stat-card text-center p-3">
            <h2 class="text-secondary mb-1">{{ $stats['total_assignments'] }}</h2>
            <p class="mb-0 text-muted small">Assignments</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-journal-check me-2 text-primary"></i>Recent Quizzes</strong>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($recent_quizzes as $quiz)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-1">
                        <span class="fw-medium">{{ $quiz->title }}</span>
                        <small class="text-muted">{{ $quiz->deadline->format('d M Y') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No quizzes yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-file-earmark-text me-2 text-warning"></i>Recent Assignments</strong>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($recent_assignments as $a)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-1">
                        <span class="fw-medium">{{ $a->title }}</span>
                        <small class="text-muted">{{ $a->deadline->format('d M Y') }}</small>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No assignments yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
