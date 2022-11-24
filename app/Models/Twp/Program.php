<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public $table = 'program_twps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'study_period_start_date', 'institution_name', 'credential',
        'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', ];
}
