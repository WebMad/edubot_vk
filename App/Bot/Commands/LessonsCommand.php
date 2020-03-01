<?php

namespace App\Bot\Commands;


use App\Operations\UserOperation;
use DateTime;

class LessonsCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $date = (new DateTime())->modify('+ 1 day');
        $date->modify('- ' . $date->format('w')-1 . ' days');
        $date_from = $date->format('Y-m-d');
        $date_to = $date->modify('+ 6 days')->format('Y-m-d');

        $dates = [];

        $user = getUser();
        $user_edu_groups = (new UserOperation())->getUserEduGroups($user->dnevnik_user_id);
        $result = "Расписание с $date_from до $date_to\n";
        $lessons = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$user_edu_groups[0]['id_str']}/lessons/$date_from/$date_to?access_token={$user->access_token}"), true);
        foreach ($lessons as $lesson) {
            if (!isset($dates[$lesson['date']])) {
                $dates[$lesson['date']] = '';
            }
            $dates[$lesson['date']] .= ' - ' . $lesson['subject']['name'] . "\n";
        }

        foreach ($dates as $key => $date) {
            $result .= $key . "\n";
            $result .= $date;
        }

        return $result;
    }
}