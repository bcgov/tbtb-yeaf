<?php

namespace App\Models\Twp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public $table = 'twp_payments';

    protected $appends = ['payment_type_readable'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['twp_student_id', 'twp_program_id', 'twp_payment_type_id', 'payment_date', 'payment_amount', 'twp_application_id'];

    public function student()
    {
        return $this->belongsTo('App\Models\Twp\Student', 'twp_student_id', 'id');
    }

    public function paymentType()
    {
        return $this->hasOne('App\Models\Twp\PaymentType', 'id', 'twp_payment_type_id');
    }

    public function getPaymentTypeReadableAttribute()
    {
        return is_null($this->paymentType) ? '' : $this->paymentType->title;
    }
}
