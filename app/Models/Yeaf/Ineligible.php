<?php

namespace App\Models\Yeaf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ineligible extends Model
{
    use HasFactory;

    public $table = 'yeaf_ineligibles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['code_id', 'active_flag', 'code_type', 'description', 'paragraph_text'];
}
