<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SetupController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\QuestionController;
use App\Http\Controllers\Teacher\QuizController as TeacherQuizController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

// First-time setup
Route::get('/setup', [SetupController::class, 'showForm'])->name('setup');
Route::post('/setup', [SetupController::class, 'create'])->name('setup.post');

// Auth
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route(auth()->user()->role . '.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Change Password (all roles) ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showForm'])->name('password.change');
    Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update');
});

// Secure Download Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/download/assignment/{assignment}', function (Assignment $assignment) {
        if (!$assignment->document_path || !Storage::disk('public')->exists($assignment->document_path)) {
            abort(404, 'Document not found.');
        }
        $fullPath = Storage::disk('public')->path($assignment->document_path);
        $fileName = $assignment->title . '.' . pathinfo($assignment->document_path, PATHINFO_EXTENSION);
        return response()->download($fullPath, $fileName);
    })->name('download.assignment');

    Route::get('/download/submission/{submission}', function (AssignmentSubmission $submission) {
        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'File not found.');
        }
        $fullPath = Storage::disk('public')->path($submission->file_path);
        $studentName = $submission->student->user->name ?? 'Student';
        $className = $submission->student->class->name ?? 'Class';
        $subjectName = $submission->assignment->subject->name ?? 'Subject';
        $extension = pathinfo($submission->file_path, PATHINFO_EXTENSION);
        $fileName = $studentName . '_' . $className . '_' . $subjectName . '_Assignment.' . $extension;
        return response()->download($fullPath, $fileName);
    })->name('download.submission');

});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('classes', ClassController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);
    Route::resource('students', StudentController::class)->except(['destroy']);

    Route::patch('students/{student}/block', [StudentController::class, 'toggleBlock'])->name('students.block');

    Route::resource('teachers', TeacherController::class)->except(['destroy']);
    Route::patch('teachers/{teacher}/block', [TeacherController::class, 'toggleBlock'])->name('teachers.block');
    Route::post('teachers/{teacher}/assign-subject', [TeacherController::class, 'assignSubject'])->name('teachers.assign-subject');
    Route::delete('teachers/{teacher}/remove-subject/{subject}', [TeacherController::class, 'removeSubject'])->name('teachers.remove-subject');

    Route::get('/reports', [AdminReport::class, 'index'])->name('reports');
    Route::get('/reports/students', [AdminReport::class, 'students'])->name('reports.students');
    Route::get('/reports/teachers', [AdminReport::class, 'teachers'])->name('reports.teachers');

});

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {

    Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');
    Route::get('/reports', [TeacherDashboard::class, 'reports'])->name('reports');

    Route::resource('questions', QuestionController::class)->except(['show']);

    Route::post('quizzes/check-questions', [TeacherQuizController::class, 'checkQuestions'])->name('quizzes.checkQuestions');

    Route::resource('quizzes', TeacherQuizController::class)->except(['destroy']);
    Route::post('quizzes/{quiz}/publish', [TeacherQuizController::class, 'publishResults'])->name('quizzes.publish');
    Route::get('quizzes/{quiz}/results', [TeacherQuizController::class, 'results'])->name('quizzes.results');

    Route::resource('assignments', TeacherAssignmentController::class)->except(['destroy']);
    Route::get('assignments/{assignment}/submissions', [TeacherAssignmentController::class, 'submissions'])->name('assignments.submissions');
    Route::get('assignments/{assignment}/submissions/{submission}/grade', [TeacherAssignmentController::class, 'gradeForm'])->name('assignments.grade.form');
    Route::post('assignments/{assignment}/submissions/{submission}/grade', [TeacherAssignmentController::class, 'grade'])->name('assignments.grade');

});

// Student Routes
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {

    Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    Route::get('/results', [StudentDashboard::class, 'results'])->name('results');

    Route::get('/quizzes', [StudentQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}/attempt', [StudentQuizController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/result', [StudentQuizController::class, 'result'])->name('quizzes.result');

    Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');

});