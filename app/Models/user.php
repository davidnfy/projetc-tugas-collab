<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Foundation\Auth\User as Authenticatable;

class user extends Model
{   
    public $timestamps = false;
    protected $table = 'users';
    protected $fillable = ['nama', 'email', 'password'];
    use HasApiTokens;
}

