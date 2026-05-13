<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    private function student()
    {
        return auth()->user()->student;
    }

    public function index()
    {
        $student     = $this->student();
        $assignments = Assignment::with(['subject', 'class'])
            ->where('class_id', $student->class_id)
            ->latest()->get();

        $submitted = AssignmentSubmission::where('student_id', $student->id)->pluck('assignment_id');

        return view('student.assignments.index', compact('assignments', 'submitted'));
    }

    public function show(Assignment $assignment)
    {
        $student    = $this->student();
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)->first();

        return view('student.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = $this->student();

        $exists = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)->exists();

        if ($exists) {
            return back()->with('error', 'You have already submitted this assignment.');
        }

        // Validate based on submission type
        if ($assignment->isTextSubmission()) {
            $request->validate([
                'solution_text' => 'required|string',
            ]);
        } else {
            $request->validate([
                'file' => 'required|file|mimes:doc,docx,pdf,jpg,jpeg,png|max:10240',
            ]);
        }

        $isLate   = $assignment->isExpired();
        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        AssignmentSubmission::create([
            'assignment_id'  => $assignment->id,
            'student_id'     => $student->id,
            'solution_text'  => $assignment->isTextSubmission() ? $request->solution_text : null,
            'file_path'      => $filePath,
            'is_late'        => $isLate,
            'is_zero_marked' => $isLate,
            'grade'          => $isLate ? 0 : null,
        ]);

        $msg = $isLate
            ? 'Submitted late — you have been given 0 marks automatically.'
            : 'Assignment submitted successfully!';

        return redirect()->route('student.assignments.show', $assignment)->with('success', $msg);
    }
}