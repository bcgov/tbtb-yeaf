<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    public $table = 'student_twps';


    public function application()
    {
        return $this->hasOne('App\Models\Twp\Application', 'twp_student_id', 'id');
    }
    public function program()
    {
        return $this->hasOne('App\Models\Twp\Program', 'twp_student_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Twp\Payment', 'twp_student_id', 'id');
    }

}
