<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public function grants()
    {
        return $this->hasMany('App\Models\Grant', 'student_id', 'student_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'student_id', 'student_id');
    }

    public function appeals()
    {
        return $this->hasMany('App\Models\Appeal', 'student_id', 'student_id');
    }
}
