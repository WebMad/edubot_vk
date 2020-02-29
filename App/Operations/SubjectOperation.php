<?php


namespace App\Operations;


use App\HttpRequestBuilder\HttpRequest;

class SubjectOperation
{
    static public function getSubjects($edu_group_id)
    {
        $user = getUser();
        return HttpRequest::init('edu-groups/:edu_group_id/subjects', [
            'url_params' => [
                'edu_group_id' => $edu_group_id
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();
    }
}