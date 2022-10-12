<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Batch extends Model
{
    use HasFactory;

    protected $appends = ['batch_human_date'];

    public function getBatchHumanDateAttribute()
    {
        $date = date('F Y', strtotime($this->batch_date));

        return Str::endsWith($this->batch_date, '15') ? $date.' - Mid' : $date.' - End';
    }
}
