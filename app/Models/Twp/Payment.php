<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $table = 'payment_twps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'twp_program_id', 'payment_date', 'payment_amount'];
}
