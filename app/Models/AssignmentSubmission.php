<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id', 'student_id', 'solution_text',
        'file_path', 'grade', 'feedback', 'is_late', 'is_zero_marked',
    ];

    protected $casts = [
        'is_late'        => 'boolean',
        'is_zero_marked' => 'boolean',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
