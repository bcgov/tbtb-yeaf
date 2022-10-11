<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['student_id', 'grant_id', 'institution_id', 'application_receive_date', 'program_code', 'program_year_id',
        'study_start_date', 'study_end_date', 'age', 'officer_user_id', 'creator_user_id', 'update_user_id',
    ];


    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'student_id');
    }

    public function py()
    {
        return $this->belongsTo('App\Models\ProgramYear', 'program_year_id', 'program_year_id');
    }

    public function appeals()
    {
        return $this->hasMany('App\Models\Appeal', 'grant_id', 'grant_id');
    }

    public function grantIneligibles()
    {
        return $this->hasMany('App\Models\GrantIneligible', 'grant_id', 'grant_id');
    }

    public function grantPendingIneligibles()
    {
        return $this->hasMany('App\Models\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'P');
    }

    public function grantDeniedIneligibles()
    {
        return $this->hasMany('App\Models\GrantIneligible', 'grant_id', 'grant_id')->where('ineligible_code_type', 'D');
    }

    public function scopeIsPending($query)
    {
        return $query->where('status_code', 'P');
    }
}
