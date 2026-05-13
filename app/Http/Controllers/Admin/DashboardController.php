<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'    => Student::count(),
            'total_teachers'    => Teacher::count(),
            'total_classes'     => SchoolClass::count(),
            'total_subjects'    => Subject::count(),
            'total_quizzes'     => Quiz::count(),
            'total_assignments' => Assignment::count(),
        ];

        $recent_quizzes     = Quiz::with(['subject', 'class'])->latest()->take(5)->get();
        $recent_assignments = Assignment::with(['subject', 'class'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_quizzes', 'recent_assignments'));
    }
}
