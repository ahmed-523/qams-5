<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'subjects.class'])->latest()->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|unique:users|max:255',
            'password'    => 'required|string|min:6',
            'job_history' => 'nullable|string',
            'education'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'teacher',
            ]);

            Teacher::create([
                'user_id'     => $user->id,
                'job_history' => $request->job_history,
                'education'   => $request->education,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher registered successfully.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects.class']);
        $allSubjects = Subject::with('class')->get();
        return view('admin.teachers.show', compact('teacher', 'allSubjects'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'username'    => 'required|string|unique:users,username,' . $teacher->user_id . '|max:255',
            'job_history' => 'nullable|string',
            'education'   => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name'     => $request->name,
                'username' => $request->username,
            ]);
            if ($request->filled('password')) {
                $teacher->user->update(['password' => Hash::make($request->password)]);
            }
            $teacher->update([
                'job_history' => $request->job_history,
                'education'   => $request->education,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated.');
    }

    public function toggleBlock(Teacher $teacher)
    {
        $teacher->user->update(['is_blocked' => !$teacher->user->is_blocked]);
        $status = $teacher->user->is_blocked ? 'blocked' : 'unblocked';
        return back()->with('success', "Teacher {$status} successfully.");
    }

    public function assignSubject(Request $request, Teacher $teacher)
    {
        $request->validate(['subject_id' => 'required|exists:subjects,id']);
        $teacher->subjects()->syncWithoutDetaching([$request->subject_id]);
        return back()->with('success', 'Subject assigned.');
    }

    public function removeSubject(Teacher $teacher, Subject $subject)
    {
        $teacher->subjects()->detach($subject->id);
        return back()->with('success', 'Subject removed.');
    }
}
