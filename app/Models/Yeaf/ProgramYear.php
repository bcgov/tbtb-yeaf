<?php

namespace App\Models\Yeaf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramYear extends Model
{
    use HasFactory;

    public $table = 'yeaf_program_years';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year_start', 'year_end', 'grant_amount', 'max_years_allowed', 'min_age', 'max_age', ];
}
