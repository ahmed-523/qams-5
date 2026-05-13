<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id', 'student_id', 'score', 'total_marks',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function getPercentageAttribute(): float
    {
        return $this->total_marks > 0
            ? round(($this->score / $this->total_marks) * 100, 2)
            : 0;
    }
}
