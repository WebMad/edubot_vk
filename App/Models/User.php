<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'login',
        'password',
        'access_token',
        'user_id',
        'dnevnik_user_id',
        'vk_user_id',
        'access_token',
        'cookie'
    ];
}