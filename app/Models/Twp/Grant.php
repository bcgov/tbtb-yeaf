<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    public $table = 'twp_grants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'received_date', 'grant_status', 'grant_amount', 'grant_comments', 'twp_application_id',
        'created_by', 'updated_by', ];

    public function student()
    {
        return $this->belongsTo('App\Models\Twp\Student', 'twp_student_id', 'id');
    }
}
