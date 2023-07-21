<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public $table = 'twp_programs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'study_period_start_date', 'institution_name', 'credential',
        'program_length', 'program_length_type', 'total_estimated_cost', 'student_status', 'comments', 'credential_type',
        'institution_twp_id', 'twp_application_id', 'study_field', ];

    public function student()
    {
        return $this->belongsTo('App\Models\Twp\Student', 'twp_student_id', 'id');
    }

    public function application()
    {
        return $this->belongsTo('App\Models\Twp\Application', 'twp_application_id', 'id');
    }

    public function institution()
    {
        return $this->belongsTo('App\Models\Twp\Institution', 'institution_twp_id', 'id');
    }

    public function versions()
    {
        return $this->hasMany('App\Models\Twp\ProgramHistory', 'program_twp_id', 'id')->orderBy('created_at', 'desc');
    }
}
