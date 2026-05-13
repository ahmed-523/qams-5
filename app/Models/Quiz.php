<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title', 'subject_id', 'class_id', 'teacher_id',
        'deadline', 'total_marks', 'is_result_published',
    ];

    protected $casts = [
        'deadline'            => 'datetime',
        'is_result_published' => 'boolean',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->deadline);
    }
}
