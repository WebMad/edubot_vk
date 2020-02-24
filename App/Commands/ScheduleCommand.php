<?php

namespace App\Commands;

use App\Operations\UserOperation;
use DateTime;

class ScheduleCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $date = (new DateTime())->modify('+ 1 day');
        $date->modify('- ' . $date->format('w') . ' days');
        $date_from = $date->format('Y-m-d');
        $date_to = $date->modify('+ 7 days')->format('Y-m-d');

        $user = getUser();
        $user_edu_groups = (new UserOperation())->getUserEduGroups($user->dnevnik_user_id);
        $result = "Расписание с $date_from до $date_to\n";
        $http_result = file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$user_edu_groups[0]['id_str']}/lessons/$date_from/$date_to?access_token={$user->access_tokenhf}");
        foreach ($http_result['days'] as $day) {

        }


        return ;
    }
}