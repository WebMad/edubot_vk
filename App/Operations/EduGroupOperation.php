<?php

namespace App\Operations;

use DateTime;

class EduGroupOperation
{
    /**
     * @param int $group_id id учебной группы
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentPeriod($group_id)
    {
        $date = new DateTime();
        $user = getUser();
        $period = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$group_id}/reporting-period-group?access_token={$user->access_token}"), true);
        foreach ($period['reportingPeriods'] as $reporting_period) {
            if ((new DateTime($reporting_period['start'])) <= $date and $date < (new DateTime($reporting_period['finish']))) {
                $period = $reporting_period;
                break;
            }
        }

        return $period;
    }
}