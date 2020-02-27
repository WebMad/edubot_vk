<?php

namespace App\Commands;

use App\Operations\UserOperation;
use DateTime;

class ScheduleCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $date = new DateTime();
        $date_from = $date->format('Y-m-d');
        $date_to = $date->modify('+ 2 days')->format('Y-m-d');

        $user = getUser();

        $user_operation = new UserOperation();

        $user_info = $user_operation->getUserInfo($user->dnevnik_user_id);

        $dic = getDic();

        $user_edu_groups = (new UserOperation())->getUserEduGroups($user->dnevnik_user_id);

        $subjects = [];
        $api_subjects = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$user_edu_groups[0]['id_str']}/subjects?access_token={$user->access_token}"), true);
        foreach ($api_subjects as $subject) {
            $subjects[$subject['id']] = $subject['name'];
        }

        $result = "Расписание с $date_from до $date_to\n";
        $http_result = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info['personId']}/groups/{$user_edu_groups[0]['id_str']}/schedules?startDate={$date_from}&endDate={$date_to}&access_token={$user->access_token}"), true);
//        var_dump($http_result);
        foreach ($http_result['days'] as $day) {
            $date = new DateTime($day['date']);
            $result .= "\n\n";
            $result .= $dic['days_of_week'][$date->format('w')] . ": \n";
            $lessons = $day['lessons'];
            foreach ($lessons as $lesson) {
                $result .= "{$dic['lesson_numbers'][$lesson['number']]} {$subjects[$lesson['subjectId']]} | {$lesson['hours']}";
                if (!empty($lesson['place'])) {
                    $result .= " | каб. {$lesson['place']}";
                }
                $result .= "\n";
            }
        }

        return $result;
    }
}