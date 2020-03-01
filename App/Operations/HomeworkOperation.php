<?php


namespace App\Operations;


use App\HttpRequestBuilder\HttpRequest;
use App\Objects\WorkObject;

class HomeworkOperation
{
    /**
     * @param $school_id
     * @param $from
     * @param $to
     * @return WorkObject[]
     */
    static public function getHomeworkByDate($school_id, $from, $to)
    {
        $user = getUser();
        return HttpRequest::init('users/me/school/:school_id/homeworks', [
            'url_params' => [
                'school_id' => $school_id,
            ],
            'args' => [
                'access_token' => $user->access_token,
                'startDate' => $from,
                'endDate' => $to,
            ]
        ])->execute();
    }

    /**
     * @param $homeworks_ids
     * @return WorkObject[]
     */
    static public function getHomeworksByIds($homeworks_ids)
    {
        $user = getUser();
        return HttpRequest::init('users/me/school/homeworks', [
            'args' => [
                'access_token' => $user->access_token,
                'homeworkId' => $homeworks_ids,
            ]
        ])->execute();
    }

    /**
     * @param $person_id
     * @param $school_id
     * @param $from
     * @param $to
     * @return WorkObject
     */
    static public function getHomeworkByPerson($person_id, $school_id, $from, $to)
    {
        $user = getUser();
        return HttpRequest::init('persons/:person_id/school/:school_id/homeworks ', [
            'url_params' => [
                'person_id' => $person_id,
                'school_id' => $school_id,
            ],
            'args' => [
                'access_token' => $user->access_token,
                'startDate' => $from,
                'endDate' => $to,
            ]
        ])->execute();
    }
}