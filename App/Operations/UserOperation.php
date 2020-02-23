<?php

namespace App\Operations;


class UserOperation
{
    public function getUserInfo($user_id)
    {
        $user = getUser();
        return json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/users/$user_id?access_token={$user->access_token}"), true);
    }

    public function getUserRoles($user_id)
    {
        $user = getUser();
        return json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/users/$user_id/roles?access_token={$user->access_token}"), true);
    }

    /**
     * @param array $users_ids
     */
    public function getUsersInfo($users_ids = [])
    {
        $ch = curl_init(); //TODO: написать функцию для получения множества пользователей
    }
}