<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrantIneligible extends Model
{
    use HasFactory;

    public function ineligible()
    {
        return $this->belongsTo('App\Models\Ineligible', 'ineligible_code_id', 'code_id');
    }
}
