@extends('layouts.app')
@section('title', 'Student Reports')
@section('page-title', 'Student Reports')
@section('sidebar')
    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
    <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
    <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
    <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
    <a href="{{ route('admin.reports') }}" class="active"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label">Filter by Class</label>
                <select name="class_id" class="form-select" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <label class="form-label">Select Student</label>
                <select name="student_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Student --</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ $studentId == $s->id ? 'selected' : '' }}>{{ $s->user->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@if($report)
<div class="row g-3">
    <div class="col-12 col-md-4">
        <div class="card p-3 text-center">
            <i class="bi bi-person-circle mb-2" style="font-size:2.5rem;color:#1e3a5f"></i>
            <h6 class="fw-bold">{{ $report->user->name }}</h6>
            <p class="mb-1 text-muted small">{{ $report->admission_number }}</p>
            <p class="mb-0"><strong>Class:</strong> {{ $report->class->name ?? '-' }}</p>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-white border-bottom"><strong>Quiz Results</strong></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Quiz</th><th>Subject</th><th>Score</th><th>%</th></tr></thead>
                    <tbody>
                        @forelse($report->quizAttempts as $a)
                        <tr>
                            <td>{{ $a->quiz->title ?? '-' }}</td>
                            <td>{{ $a->quiz->subject->name ?? '-' }}</td>
                            <td>{{ $a->score }}/{{ $a->total_marks }}</td>
                            <td>{{ $a->total_marks > 0 ? round(($a->score/$a->total_marks)*100,1) : 0 }}%</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted text-center py-3">No attempts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-white border-bottom"><strong>Assignment Results</strong></div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Assignment</th><th>Subject</th><th>Grade</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($report->assignmentSubmissions as $sub)
                        <tr>
                            <td>{{ $sub->assignment->title ?? '-' }}</td>
                            <td>{{ $sub->assignment->subject->name ?? '-' }}</td>
                            <td>{{ $sub->grade ?? '-' }}</td>
                            <td>
                                @if($sub->is_zero_marked)<span class="badge bg-danger">Late/Zero</span>
                                @elseif($sub->is_late)<span class="badge bg-warning text-dark">Late</span>
                                @else<span class="badge bg-success">On Time</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-muted text-center py-3">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Select a student above to view their report.</div>
@endif
@endsection
