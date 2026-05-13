<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subject_id', 'teacher_id', 'question_text',
        'question_type', 'options', 'correct_answer', 'marks',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions');
    }
}
