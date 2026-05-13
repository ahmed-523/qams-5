@extends('layouts.app')
@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('sidebar')
    @if(auth()->user()->role === 'teacher')
        <a href="{{ route('teacher.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('teacher.questions.index') }}"><i class="bi bi-question-circle me-2"></i>Question Bank</a>
        <a href="{{ route('teacher.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>Quizzes</a>
        <a href="{{ route('teacher.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Assignments</a>
        <a href="{{ route('teacher.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
    @elseif(auth()->user()->role === 'student')
        <a href="{{ route('student.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('student.quizzes.index') }}"><i class="bi bi-journal-check me-2"></i>My Quizzes</a>
        <a href="{{ route('student.assignments.index') }}"><i class="bi bi-file-earmark-text me-2"></i>My Assignments</a>
        <a href="{{ route('student.results') }}"><i class="bi bi-graph-up me-2"></i>My Results</a>
    @elseif(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('admin.classes.index') }}"><i class="bi bi-building me-2"></i>Classes</a>
        <a href="{{ route('admin.subjects.index') }}"><i class="bi bi-book me-2"></i>Subjects</a>
        <a href="{{ route('admin.students.index') }}"><i class="bi bi-people me-2"></i>Students</a>
        <a href="{{ route('admin.teachers.index') }}"><i class="bi bi-person-badge me-2"></i>Teachers</a>
        <a href="{{ route('admin.reports') }}"><i class="bi bi-bar-chart me-2"></i>Reports</a>
    @endif
@endsection

@section('content')
<div class="form-card-wrap-sm">
    <div class="card">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div style="width:60px;height:60px;background:#e8f0fe;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="bi bi-lock-fill text-primary fs-4"></i>
                </div>
                <h5 class="fw-bold mb-1">Change Your Password</h5>
                <p class="text-muted small">Enter your current password and choose a new one.</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Password *</label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="Enter current password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('current_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password *</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               placeholder="Minimum 6 characters" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('new_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('new_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm New Password *</label>
                    <div class="input-group">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                               class="form-control" placeholder="Repeat new password" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('new_password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-check-lg me-1"></i>Update Password
                </button>
                <a href="{{ url()->previous() }}" class="btn btn-light w-100 mt-2">
                    <i class="bi bi-arrow-left me-1"></i>Go Back
                </a>
            </form>
        </div>
    </div>
</div>

<script>
function togglePwd(fieldId, btn) {
    var input = document.getElementById(fieldId);
    var icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection
