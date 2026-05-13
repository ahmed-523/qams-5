<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;

class DashboardController extends Controller
{
    private function getTeacher()
    {
        return auth()->user()->teacher;
    }

    public function index()
    {
        $teacher        = $this->getTeacher()->load(['subjects.class', 'quizzes', 'assignments']);
        $recent_quizzes = $teacher->quizzes()->with('subject')->latest()->take(5)->get();
        $recent_assignments = $teacher->assignments()->with('subject')->latest()->take(5)->get();
        return view('teacher.dashboard', compact('teacher', 'recent_quizzes', 'recent_assignments'));
    }

    public function reports()
    {
        $teacher  = $this->getTeacher()->load('subjects');
        $subjects = $teacher->subjects;
        $classIds = $subjects->pluck('class_id')->unique();
        $students = Student::with(['user', 'class',
            'quizAttempts.quiz',
            'assignmentSubmissions.assignment',
        ])->whereIn('class_id', $classIds)->get();

        return view('teacher.reports', compact('students', 'teacher'));
    }
}
