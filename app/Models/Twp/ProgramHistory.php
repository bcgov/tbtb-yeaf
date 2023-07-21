<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramHistory extends Model
{
    use HasFactory;

    public $table = 'twp_program_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'study_period_start_date', 'institution_name', 'credential',
        'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
        'institution_twp_id', 'program_twp_id', 'twp_application_id', 'study_field', ];

    public function program()
    {
        return $this->belongsTo('App\Models\Twp\Program', 'program_twp_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Twp\Student', 'twp_student_id', 'id');
    }
}
