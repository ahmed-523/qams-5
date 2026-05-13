<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function students()
    {
        $classes   = SchoolClass::all();
        $classId   = request('class_id');
        $studentId = request('student_id');

        $studentsQuery = Student::with(['user', 'class']);
        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }
        $students = $studentsQuery->get();

        $report = null;
        if ($studentId) {
            $report = Student::with([
                'user', 'class',
                'quizAttempts.quiz.subject',
                'assignmentSubmissions.assignment.subject',
            ])->findOrFail($studentId);
        }

        return view('admin.reports.students', compact('classes', 'students', 'report', 'classId', 'studentId'));
    }

    public function teachers()
    {
        $teachers = Teacher::with([
            'user', 'subjects',
            'quizzes', 'assignments',
        ])->get();
        return view('admin.reports.teachers', compact('teachers'));
    }
}
