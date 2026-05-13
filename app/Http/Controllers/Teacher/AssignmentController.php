<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject; // Ye line add ki hai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    private function teacher()
    {
        return auth()->user()->teacher;
    }

    public function index()
    {
        $assignments = Assignment::with(['subject', 'class'])
            ->where('teacher_id', $this->teacher()->id)
            ->latest()
            ->get();
        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $subjects = $this->teacher()->subjects()->with('class')->get();
        // Class bhejney ki zaroorat khatam kar di
        return view('teacher.assignments.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'subject_id'      => 'required|exists:subjects,id', // Sirf subject mangwa rahe hain
            'deadline'        => 'required|date|after:now',
            'total_marks'     => 'required|integer|min:1',
            'submission_type' => 'required|in:text,file',
            'document'        => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ]);

        // System khud Subject ki madad se uski Class nikal le ga
        $subject = Subject::findOrFail($request->subject_id);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('assignments/documents', 'public');
        }

        Assignment::create([
            'title'           => $request->title,
            'description'     => $request->description,
            'subject_id'      => $request->subject_id,
            'class_id'        => $subject->class_id, // Class automatic save hogi
            'teacher_id'      => $this->teacher()->id,
            'deadline'        => $request->deadline,
            'total_marks'     => $request->total_marks,
            'submission_type' => $request->submission_type,
            'document_path'   => $documentPath,
        ]);

        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['subject', 'class']);
        return view('teacher.assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        return view('teacher.assignments.edit', compact('assignment'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'deadline'        => 'required|date',
            'total_marks'     => 'required|integer|min:1',
            'submission_type' => 'required|in:text,file',
            'document'        => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ]);

        $data = $request->only('title', 'description', 'deadline', 'total_marks', 'submission_type');

        if ($request->hasFile('document')) {
            if ($assignment->document_path) {
                Storage::disk('public')->delete($assignment->document_path);
            }
            $data['document_path'] = $request->file('document')->store('assignments/documents', 'public');
        }

        $assignment->update($data);
        return redirect()->route('teacher.assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function submissions(Assignment $assignment)
    {
        if ($assignment->isExpired()) {
            $classStudents = Student::where('class_id', $assignment->class_id)->get();
            foreach ($classStudents as $student) {
                $exists = AssignmentSubmission::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)->exists();
                if (!$exists) {
                    AssignmentSubmission::create([
                        'assignment_id'  => $assignment->id,
                        'student_id'     => $student->id,
                        'solution_text'  => null,
                        'grade'          => 0,
                        'is_late'        => true,
                        'is_zero_marked' => true,
                    ]);
                }
            }
        }

        $submissions = AssignmentSubmission::with('student.user')
            ->where('assignment_id', $assignment->id)->get();

        return view('teacher.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function gradeForm(Assignment $assignment, AssignmentSubmission $submission)
    {
        return view('teacher.assignments.grade', compact('assignment', 'submission'));
    }

    public function grade(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        $request->validate([
            'grade'    => 'required|integer|min:0|max:' . $assignment->total_marks,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade'    => $request->grade,
            'feedback' => $request->feedback,
        ]);

        return redirect()->route('teacher.assignments.submissions', $assignment)
            ->with('success', 'Grade saved successfully.');
    }
}