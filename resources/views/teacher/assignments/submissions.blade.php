@extends('layouts.app')
@section('title', 'Submissions')
@section('page-title', 'Submissions: ' . $assignment->title)
@section('sidebar')
    <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('teacher.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
    <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
    <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
    <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
@endsection
@section('content')
<div class="card mb-3">
    <div class="card-body">
        <p class="mb-1">
            <strong>Subject:</strong> {{ $assignment->subject->name ?? '-' }}
            &nbsp;|&nbsp; <strong>Total Marks:</strong> {{ $assignment->total_marks }}
        </p>
        <p class="mb-0">
            <strong>Deadline:</strong>
            <span class="{{ $assignment->isExpired() ? 'text-danger' : 'text-success' }}">
                {{ $assignment->deadline->format('d M Y, h:i A') }}
            </span>
        </p>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th class="d-none d-md-table-cell">Answer</th>
                    <th>Status</th>
                    <th>Grade</th>
                    <th class="d-none d-lg-table-cell">Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $sub)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sub->student->user->name ?? '-' }}</td>
                    <td class="d-none d-md-table-cell">
                        @if($sub->solution_text)
                            {{ Str::limit($sub->solution_text, 40) }}
                        @elseif($sub->file_path)
                            <a href="{{ route('download.submission', $sub) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                        @else
                            <em class="text-muted">No submission</em>
                        @endif
                    </td>
                    <td>
                        @if($sub->is_zero_marked)<span class="badge bg-danger">Late/Zero</span>
                        @elseif($sub->is_late)<span class="badge bg-warning text-dark">Late</span>
                        @else<span class="badge bg-success">On Time</span>@endif
                    </td>
                    <td>{{ $sub->grade ?? '-' }}/{{ $assignment->total_marks }}</td>
                    <td class="d-none d-lg-table-cell">{{ $sub->feedback ? Str::limit($sub->feedback, 30) : '-' }}</td>
                    <td>
                        @if(!$sub->is_zero_marked)
                        <a href="{{ route('teacher.assignments.grade.form', [$assignment, $sub]) }}"
                           class="btn btn-sm btn-outline-primary"
                           data-bs-toggle="tooltip" title="Grade this submission">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        @else
                        <span class="text-muted small">Auto-zero</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
