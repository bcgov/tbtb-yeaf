<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'student_id');
    }

    public function py()
    {
        return $this->belongsTo('App\Models\ProgramYear', 'program_year_id', 'program_year_id');
    }

    public function scopeIsPending($query)
    {
        return $query->where('status_code', 'P');
    }
}
