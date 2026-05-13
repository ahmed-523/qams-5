<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title', 'description', 'subject_id', 'class_id',
        'teacher_id', 'deadline', 'total_marks', 'document_path', 'submission_type',
    ];

    protected $casts = [
        'deadline' => 'datetime',
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

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->deadline);
    }

    public function isTextSubmission(): bool
    {
        return $this->submission_type === 'text';
    }

    public function isFileSubmission(): bool
    {
        return $this->submission_type === 'file';
    }
}