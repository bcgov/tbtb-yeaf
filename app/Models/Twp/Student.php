<?php

namespace App\Models\Twp;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $appends = ['age', 'app_status'];

    public $table = 'twp_students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name', 'first_name', 'birth_date', 'email', 'gender', 'pen', 'address', 'citizenship',
        'bc_resident', 'indigeneity', 'address', 'comment', 'sin', ];

    public function applications()
    {
        return $this->hasMany('App\Models\Twp\Application', 'twp_student_id', 'id');
    }

    public function programs()
    {
        return $this->hasMany('App\Models\Twp\Program', 'twp_student_id', 'id');
    }

    public function application()
    {
        return $this->hasOne('App\Models\Twp\Application', 'twp_student_id', 'id');
    }

    public function getAgeAttribute()
    {
        if (is_null($this->birth_date)) {
            return null;
        }

        return (new Carbon($this->birth_date))->age;
    }

    public function getAppStatusAttribute()
    {
        return is_null($this->application) ? '' : $this->application->application_status;
    }
}
