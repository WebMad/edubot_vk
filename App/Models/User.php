<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    const DATA_RESTRICT_ASK = 0;
    const DATA_ACCESSED = 1;
    const DATA_RESTRICT = 2;
    protected $table = 'users';
    protected $fillable = [
        'login',
        'password',
        'access_token',
        'dnevnik_user_id',
        'vk_user_id',
        'access_token',
        'cookie_file',
        'personal_data_access'
    ];
}