@extends('layouts.app')
@section('title', 'Assign Subjects')
@section('page-title', 'Assign Subjects — ' . $teacher->user->name)
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}" class="active"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')

<div class="card mb-4">
    <div class="card-body d-flex align-items-center gap-3 flex-wrap">
        <i class="bi bi-person-badge flex-shrink-0" style="font-size:2.5rem;color:#1e3a5f"></i>
        <div class="flex-grow-1">
            <h5 class="mb-0">{{ $teacher->user->name }}</h5>
            <span class="text-muted me-2"><code>{{ $teacher->user->username }}</code></span>
            <span class="badge {{ $teacher->user->is_blocked ? 'bg-danger' : 'bg-success' }}">
                {{ $teacher->user->is_blocked ? 'Blocked' : 'Active' }}
            </span>
            @if($teacher->education)
                <div class="text-muted small mt-1"><i class="bi bi-mortarboard me-1"></i>{{ $teacher->education }}</div>
            @endif
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Teachers
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-check-circle-fill text-success me-2"></i>Currently Assigned Subjects</strong>
            </div>
            <div class="card-body">
                @forelse($teacher->subjects as $subject)
                <div class="d-flex align-items-center justify-content-between border rounded p-2 mb-2 flex-wrap gap-2">
                    <div>
                        <span class="fw-semibold">{{ $subject->name }}</span>
                        <span class="text-muted small ms-2">({{ $subject->class->name ?? 'No class' }})</span>
                    </div>
                    <form action="{{ route('admin.teachers.remove-subject', [$teacher, $subject]) }}" method="POST"
                          onsubmit="return confirm('Remove {{ $subject->name }} from this teacher?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Remove
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-book" style="font-size:2rem"></i>
                    <p class="mt-2 mb-0">No subjects assigned yet.<br>Use the form below to assign subjects.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white border-bottom">
                <strong><i class="bi bi-plus-circle-fill text-primary me-2"></i>Assign a New Subject</strong>
            </div>
            <div class="card-body">
                @if($allSubjects->isEmpty())
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No subjects exist yet.
                        <a href="{{ route('admin.subjects.create') }}">Create subjects first</a>.
                    </div>
                @else
                    <form action="{{ route('admin.teachers.assign-subject', $teacher) }}" method="POST">
                        @csrf
                        <label class="form-label fw-semibold">Select Subject to Assign</label>

                        @php
                            $grouped = $allSubjects->groupBy(fn($s) => $s->class->name ?? 'No Class');
                            $alreadyAssigned = $teacher->subjects->pluck('id')->toArray();
                        @endphp

                        <select name="subject_id" class="form-select mb-3" required>
                            <option value="">-- Select a Subject --</option>
                            @foreach($grouped as $className => $subjects)
                                <optgroup label="{{ $className }}">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ in_array($subject->id, $alreadyAssigned) ? 'disabled' : '' }}>
                                            {{ $subject->name }}
                                            {{ in_array($subject->id, $alreadyAssigned) ? '(already assigned)' : '' }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg me-1"></i>Assign This Subject
                        </button>
                    </form>

                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Subjects are grouped by class. Already-assigned subjects are shown as disabled.
                        To add more subjects, go to <a href="{{ route('admin.subjects.create') }}">Create Subject</a>.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
