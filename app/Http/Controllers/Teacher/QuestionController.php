<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private function teacherSubjects()
    {
        return auth()->user()->teacher->subjects;
    }

    public function index(Request $request)
    {
        $subjects  = $this->teacherSubjects();
        $subjectId = $request->get('subject_id');

        $questions = Question::with('subject')
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->when($subjectId, fn($q) => $q->where('subject_id', $subjectId))
            ->latest()
            ->get();

        return view('teacher.questions.index', compact('questions', 'subjects', 'subjectId'));
    }

    public function create()
    {
        $subjects = $this->teacherSubjects();
        return view('teacher.questions.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        // ✅ FIXED VALIDATION: options.* ab sirf MCQ ke liye required honge
        $request->validate([
            'subject_id'     => 'required|exists:subjects,id',
            'question_text'  => 'required|string',
            'question_type'  => 'required|in:mcq,true_false,short',
            'correct_answer' => 'required|string',
            'marks'          => 'required|integer|min:1',
            'options'        => 'required_if:question_type,mcq|array',
            'options.*'      => 'required_if:question_type,mcq|string', 
        ]);

        $options = null;
        if ($request->question_type === 'mcq') {
            $options = array_filter($request->options ?? []);
        } elseif ($request->question_type === 'true_false') {
            // ✅ True/False ke liye automatic options set kar diye
            $options = ['True', 'False'];
        }

        Question::create([
            'subject_id'     => $request->subject_id,
            'teacher_id'     => auth()->id(),
            'question_text'  => $request->question_text,
            'question_type'  => $request->question_type,
            'options'        => $options,
            'correct_answer' => $request->correct_answer,
            'marks'          => $request->marks,
        ]);

        return redirect()->route('teacher.questions.index')->with('success', 'Question added to bank.');
    }

    public function edit(Question $question)
    {
        $subjects = $this->teacherSubjects();
        return view('teacher.questions.edit', compact('question', 'subjects'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text'  => 'required|string',
            'correct_answer' => 'required|string',
            'marks'          => 'required|integer|min:1',
            'options'        => 'required_if:question_type,mcq|array',
            'options.*'      => 'required_if:question_type,mcq|string',
        ]);

        $options = $question->options;
        if ($request->question_type === 'mcq') {
            $options = array_filter($request->options ?? []);
        }

        $question->update([
            'question_text'  => $request->question_text,
            'options'        => $options,
            'correct_answer' => $request->correct_answer,
            'marks'          => $request->marks,
        ]);

        return redirect()->route('teacher.questions.index')->with('success', 'Question updated.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('teacher.questions.index')->with('success', 'Question deleted.');
    }
}