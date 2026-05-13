<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    private function teacher()
    {
        return auth()->user()->teacher;
    }

    public function index()
    {
        $quizzes = Quiz::with(['subject', 'class'])
            ->where('teacher_id', $this->teacher()->id)
            ->latest()
            ->get();
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $subjects = $this->teacher()->subjects()->with('class')->get();
        $classes  = SchoolClass::all();
        return view('teacher.quizzes.create', compact('subjects', 'classes'));
    }

    /**
     * AJAX: Check if the question bank has enough questions.
     * Uses auth()->id() (users.id = 3) NOT $this->teacher()->id (teachers.id = 1)
     * because questions table stores users.id in teacher_id column.
     */
    public function checkQuestions(Request $request)
    {
        $request->validate([
            'subject_id'          => 'required|exists:subjects,id',
            'number_of_questions' => 'required|integer|min:1',
        ]);

        // ✅ auth()->id() matches questions.teacher_id (users.id)
        $available = Question::where('subject_id', $request->subject_id)
            ->where('teacher_id', auth()->id())
            ->count();

        $requested = (int) $request->number_of_questions;

        return response()->json([
            'enough'    => $available >= $requested,
            'available' => $available,
            'requested' => $requested,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'subject_id'          => 'required|exists:subjects,id',
            'class_id'            => 'required|exists:classes,id',
            'deadline'            => 'required|date|after:now',
            'number_of_questions' => 'required|integer|min:1',
        ]);

        $requested = (int) $request->number_of_questions;

        // ✅ auth()->id() matches questions.teacher_id (users.id)
        $allQuestions = Question::where('subject_id', $request->subject_id)
            ->where('teacher_id', auth()->id())
            ->get();

        // Server-side guard
        if ($allQuestions->count() < $requested) {
            return back()
                ->withInput()
                ->withErrors([
                    'number_of_questions' =>
                        "Not enough questions in the bank. You requested {$requested} but only "
                        . $allQuestions->count() . " are available. Please add more questions first.",
                ]);
        }

        // Randomly pick N questions
        $selectedQuestions = $allQuestions->shuffle()->take($requested);
        $totalMarks        = $selectedQuestions->sum('marks');

        $quiz = Quiz::create([
            'title'       => $request->title,
            'subject_id'  => $request->subject_id,
            'class_id'    => $request->class_id,
            'teacher_id'  => $this->teacher()->id,  // quizzes table uses teachers.id ✅
            'deadline'    => $request->deadline,
            'total_marks' => $totalMarks,
        ]);

        $quiz->questions()->attach($selectedQuestions->pluck('id')->toArray());

        return redirect()
            ->route('teacher.quizzes.index')
            ->with('success', "Quiz created with {$requested} randomly selected questions (Total marks: {$totalMarks}).");
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['subject', 'class', 'questions']);
        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        return view('teacher.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);

        $quiz->update([
            'title'    => $request->title,
            'deadline' => $request->deadline,
        ]);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz updated (deadline extended).');
    }

    public function publishResults(Quiz $quiz)
    {
        $quiz->update(['is_result_published' => true]);
        return back()->with('success', 'Quiz results published to students.');
    }

    public function results(Quiz $quiz)
    {
        $quiz->load('attempts.student.user');
        return view('teacher.quizzes.results', compact('quiz'));
    }
}