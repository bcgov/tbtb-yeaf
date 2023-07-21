<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'password_resets';

    protected $fillable = ['email', 'token', 'created_at'];

    const UPDATED_AT = null;
}
