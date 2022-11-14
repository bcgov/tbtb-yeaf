<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    public const IS_SUPER_ADMIN = 1;
    public const IS_YEAF_USER = 2;
    public const IS_YEAF_ADMIN = 3;
    public const IS_TWP_ADMIN = 4;
    public const IS_TWP_USER = 5;


    /**
     * The roles that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_user');
    }
}
