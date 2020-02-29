<?php


namespace App\Operations;


use App\HttpRequestBuilder\HttpRequest;

class ScheduleOperation
{
    static public function getSchedule($person_id, $edu_group_id, $date_from, $date_to)
    {
        $user = getUser();
        return HttpRequest::init('persons/:person_id/groups/:edu_group_id/schedules', [
            'url_params' => [
                'person_id' => $person_id,
                'edu_group_id' => $edu_group_id,
            ],
            'args' => [
                'startDate' => $date_from,
                'endDate' => $date_to,
                'access_token' => $user->access_token
            ]
        ])->execute();
    }
}