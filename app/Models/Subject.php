<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'class_id'];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
