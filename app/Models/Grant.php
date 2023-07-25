<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grant extends Model
{
    use HasFactory;

    public function scopeIsPending($query)
    {
        return $query->where('status_code', 'P');
    }
}
