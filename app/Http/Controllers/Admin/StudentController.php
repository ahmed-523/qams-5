<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'class'])->latest()->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'username'         => 'required|string|unique:users|max:255',
            'password'         => 'required|string|min:6',
            'admission_number' => 'required|string|unique:students',
            'father_name'      => 'nullable|string',
            'class_id'         => 'nullable|exists:classes,id',
            'picture'          => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $picturePath = null;
            if ($request->hasFile('picture')) {
                $picturePath = $request->file('picture')->store('students', 'public');
            }

            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'student',
            ]);

            Student::create([
                'user_id'          => $user->id,
                'admission_number' => $request->admission_number,
                'father_name'      => $request->father_name,
                'class_id'         => $request->class_id,
                'picture'          => $picturePath,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student registered successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'class', 'quizAttempts.quiz', 'assignmentSubmissions.assignment']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::all();
        $student->load('user');
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'username'         => 'required|string|unique:users,username,' . $student->user_id . '|max:255',
            'admission_number' => 'required|string|unique:students,admission_number,' . $student->id,
            'father_name'      => 'nullable|string',
            'class_id'         => 'nullable|exists:classes,id',
        ]);

        DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name'     => $request->name,
                'username' => $request->username,
            ]);

            if ($request->filled('password')) {
                $student->user->update(['password' => Hash::make($request->password)]);
            }

            $studentData = [
                'admission_number' => $request->admission_number,
                'father_name'      => $request->father_name,
                'class_id'         => $request->class_id,
            ];

            if ($request->hasFile('picture')) {
                $studentData['picture'] = $request->file('picture')->store('students', 'public');
            }

            $student->update($studentData);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated.');
    }

    public function toggleBlock(Student $student)
    {
        $student->user->update(['is_blocked' => !$student->user->is_blocked]);
        $status = $student->user->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "Student {$status} successfully.");
    }
}
