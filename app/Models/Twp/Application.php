<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public $table = 'application_twps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'received_date', 'application_status', 'twp_status', 'denial_reason', 'exception_comments'];

    public function reasons()
    {
        return $this->belongsToMany(
            'App\Models\Twp\Reason', // The model to access to
            'App\Models\Twp\ApplicationReason', // The intermediate table that connects the Application with the Reason.
            'application_id',                 // The column of the intermediate table that connects to this model by its ID.
            'reason_id',              // The column of the intermediate table that connects the Reason by its ID.
            'id',                      // The column that connects this model with the intermediate model table.
            'id'               // The column of the Reason table that ties it to the Application.
        );
    }
}
