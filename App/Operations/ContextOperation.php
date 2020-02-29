<?php


namespace App\Operations;


use App\HttpRequestBuilder\HttpRequest;
use App\Objects\ContextObject\ContextObject;

class ContextOperation
{
    /**
     * @return ContextObject
     */
    static function me()
    {
        $user = getUser();
        return HttpRequest::init('users/me/context', [
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }

    /**
     * @param $dnevnik_user_id
     * @return mixed
     */
    static function contextByDnevnikUserId($dnevnik_user_id)
    {
        $user = getUser();
        return HttpRequest::init('users/:dnevnik_user_id/context', [
            'url_params' => [
                'dnevnik_user_id' => $dnevnik_user_id
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }
}