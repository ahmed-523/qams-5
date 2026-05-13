@extends('layouts.app')
@section('title', 'My Quizzes')
@section('page-title', 'My Quizzes')
@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}" class="active"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection
@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Quiz Title</th>
                    <th class="d-none d-md-table-cell">Subject</th>
                    <th class="d-none d-sm-table-cell">Marks</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $quiz->title }}</strong>
                        <div class="d-md-none text-muted small">{{ $quiz->subject->name ?? '-' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $quiz->subject->name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $quiz->total_marks }}</td>
                    <td class="{{ $quiz->isExpired() ? 'text-danger' : 'text-success' }}" style="white-space:nowrap">
                        {{ $quiz->deadline->format('d M Y') }}
                    </td>
                    <td>
                        @if($attempted->contains($quiz->id))
                            <span class="badge bg-success">Attempted</span>
                        @elseif($quiz->isExpired())
                            <span class="badge bg-danger">Expired</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($attempted->contains($quiz->id))
                            @if($quiz->is_result_published)
                                <a href="{{ route('student.quizzes.result', $quiz) }}"
                                   class="btn btn-sm btn-outline-info"
                                   data-bs-toggle="tooltip" title="View Result">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @else
                                <span class="badge bg-secondary"
                                      data-bs-toggle="tooltip"
                                      title="Result will be visible once teacher publishes it">
                                    <i class="bi bi-hourglass-split me-1"></i><span class="d-none d-sm-inline">Awaiting</span>
                                </span>
                            @endif
                        @elseif(!$quiz->isExpired())
                            <a href="{{ route('student.quizzes.attempt', $quiz) }}"
                               class="btn btn-sm btn-primary"
                               data-bs-toggle="tooltip" title="Attempt this quiz">
                                <i class="bi bi-pencil-square me-1"></i><span class="d-none d-sm-inline">Attempt</span>
                            </a>
                        @else
                            <span class="text-muted small">Missed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No quizzes assigned to your class yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
