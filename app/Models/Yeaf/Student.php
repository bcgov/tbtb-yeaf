<?php

namespace App\Models\Yeaf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $table = 'yeaf_students';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id', 'sin', 'last_name', 'first_name', 'address', 'city', 'birth_date', 'country', 'province',
        'postal_code', 'tele', 'email', 'gender', 'pen', 'institution_student_number', 'overaward_flag', 'investigate', 'pd', ];

    public function grants()
    {
        return $this->hasMany('App\Models\Yeaf\Grant', 'student_id', 'student_id')->orderBy('grant_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Yeaf\Comment', 'student_id', 'student_id');
    }

    public function appeals()
    {
        return $this->hasMany('App\Models\Yeaf\Appeal', 'student_id', 'student_id');
    }
}
