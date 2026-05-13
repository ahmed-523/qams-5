<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Assignment;
use App\Models\QuizAttempt;

class DashboardController extends Controller
{
    private function student()
    {
        return auth()->user()->student;
    }

    public function index()
    {
        $student = $this->student()->load(['class']);
        $classId = $student->class_id;

        // Get ALL quizzes for this class (not just upcoming)
        $all_quizzes = Quiz::where('class_id', $classId)
            ->with('subject')
            ->latest()
            ->get();

        // Pending assignments (still have time to submit)
        $upcoming_assignments = Assignment::where('class_id', $classId)
            ->where('deadline', '>', now())
            ->with('subject')
            ->latest()
            ->get();

        // Get ALL attempt and submission IDs
        $attempted_quiz_ids = QuizAttempt::where('student_id', $student->id)->pluck('quiz_id');
        $submitted_assignment_ids = $student->assignmentSubmissions()->pluck('assignment_id');

        return view('student.dashboard', compact(
            'student', 'all_quizzes', 'upcoming_assignments',
            'attempted_quiz_ids', 'submitted_assignment_ids'
        ));
    }

    public function results()
    {
        $student = $this->student()->load([
            'quizAttempts.quiz.subject',
            'assignmentSubmissions.assignment.subject',
        ]);

        $quizAttempts = $student->quizAttempts;
        $submissions  = $student->assignmentSubmissions;

        $totalAttemptsCount = $quizAttempts->count();

        $publishedAttempts = $quizAttempts->filter(fn($a) => $a->quiz && $a->quiz->is_result_published);

        $avgQuiz = $publishedAttempts->count() > 0
            ? round($publishedAttempts->avg(fn($a) => $a->total_marks > 0 ? ($a->score / $a->total_marks) * 100 : 0), 1)
            : 0;

        $gradedSubmissions = $submissions->whereNotNull('grade');
        $avgAssignment = $gradedSubmissions->count() > 0
            ? round($gradedSubmissions->avg('grade'), 1)
            : 0;

        return view('student.results', compact('student', 'quizAttempts', 'submissions', 'avgQuiz', 'avgAssignment', 'totalAttemptsCount'));
    }
}