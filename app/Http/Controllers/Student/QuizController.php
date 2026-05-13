<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    private function student()
    {
        return auth()->user()->student;
    }

    public function index()
    {
        $student = $this->student();
        $quizzes = Quiz::with(['subject', 'class'])
            ->where('class_id', $student->class_id)
            ->latest()->get();

        $attempted = QuizAttempt::where('student_id', $student->id)->pluck('quiz_id');

        return view('student.quizzes.index', compact('quizzes', 'attempted'));
    }

    public function attempt(Quiz $quiz)
    {
        $student = $this->student();

        if ($quiz->class_id !== $student->class_id) {
            abort(403);
        }

        $alreadyAttempted = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.quizzes.result', $quiz)
                ->with('info', 'You have already attempted this quiz.');
        }

        if ($quiz->isExpired()) {
            return back()->with('error', 'This quiz deadline has passed.');
        }

        $quiz->load('questions');
        return view('student.quizzes.attempt', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $student = $this->student();

        $alreadyAttempted = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)->exists();

        if ($alreadyAttempted) {
            return redirect()->route('student.quizzes.result', $quiz);
        }

        if ($quiz->isExpired()) {
            return back()->with('error', 'Deadline has passed.');
        }

        $quiz->load('questions');
        $score = 0;

        DB::transaction(function () use ($request, $quiz, $student, &$score) {
            $attempt = QuizAttempt::create([
                'quiz_id'     => $quiz->id,
                'student_id'  => $student->id,
                'score'       => 0,
                'total_marks' => $quiz->total_marks,
            ]);

            foreach ($quiz->questions as $question) {
                $answer    = $request->input('answers.' . $question->id, '');
                $isCorrect = strtolower(trim($question->correct_answer)) === strtolower(trim($answer));
                $marks     = $isCorrect ? $question->marks : 0;
                $score     += $marks;

                QuizAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id'     => $question->id,
                    'answer'          => $answer,
                    'is_correct'      => $isCorrect,
                    'marks_obtained'  => $marks,
                ]);
            }

            $attempt->update(['score' => $score]);
        });

        // No flash message — result.blade.php already shows the confirmation UI
        return redirect()->route('student.quizzes.result', $quiz);
    }

    public function result(Quiz $quiz)
    {
        $student = $this->student();
        $attempt = QuizAttempt::with('answers.question')
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$attempt) {
            return redirect()->route('student.quizzes.index')->with('error', 'No attempt found.');
        }

        return view('student.quizzes.result', compact('quiz', 'attempt'));
    }
}