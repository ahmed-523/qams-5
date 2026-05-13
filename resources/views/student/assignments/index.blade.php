@extends('layouts.app')
@section('title', 'My Assignments')
@section('page-title', 'My Assignments')
@section('sidebar')
    <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
    <a href="{{ route('student.assignments.index') }}" class="active"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
    <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
@endsection
@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th class="d-none d-md-table-cell">Subject</th>
                    <th class="d-none d-sm-table-cell">Marks</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $a)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $a->title }}</strong>
                        <div class="d-md-none text-muted small">{{ $a->subject->name ?? '-' }}</div>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $a->subject->name ?? '-' }}</td>
                    <td class="d-none d-sm-table-cell">{{ $a->total_marks }}</td>
                    <td class="{{ $a->isExpired() ? 'text-danger' : 'text-success' }}" style="white-space:nowrap">
                        {{ $a->deadline->format('d M Y') }}
                    </td>
                    <td>
                        @if($submitted->contains($a->id))
                            <span class="badge bg-success">Submitted</span>
                        @elseif($a->isExpired())
                            <span class="badge bg-danger">Expired</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('student.assignments.show', $a) }}"
                           class="btn btn-sm {{ $submitted->contains($a->id) ? 'btn-outline-info' : 'btn-primary' }}">
                            {{ $submitted->contains($a->id) ? 'View' : 'Submit' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No assignments for your class yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
