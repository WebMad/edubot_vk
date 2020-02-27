<?php

namespace App\Commands;

use App\Operations\UserOperation;
use DateTime;

class HomeworkCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $date = new DateTime();

        $user = getUser();

        $user_operation = new UserOperation();

        $user_info = $user_operation->getContext();

        $edu_group = $user_info['eduGroups'][0];

        $schedule = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info['personId']}/groups/{$edu_group['id_str']}/schedules?startDate={$date->format('Y-m-d')}&endDate={$date->format('Y-m-d')}&access_token={$user->access_token}"), true)['days'][0]['lessons'];

        $last_lesson = $schedule[count($schedule) - 1];

        $hours = trim(substr($last_lesson['hours'], stripos($last_lesson['hours'], '-') + 2));

        $school = $user_info['schools'][0];

        if ($date >= new DateTime($hours)) {
            $homeworks = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/users/me/school/{$school['id']}/homeworks?startDate={$date->modify('+1 day')->format('Y-m-d')}&endDate={$date->format('Y-m-d')}&access_token={$user->access_token}"), true);
        } else {
            $homeworks = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/users/me/school/{$school['id']}/homeworks?startDate={$date->format('Y-m-d')}&endDate={$date->format('Y-m-d')}&access_token={$user->access_token}"), true);
        }

        $works_api = $homeworks['works'];
        $works = [];
        foreach ($works_api as $work) {
            $works[$work['lesson']][] = $work['text'];
        }

        $subjects_api = $homeworks['subjects'];
        $subjects = [];
        foreach ($subjects_api as $subject) {
            $subjects[$subject['id']] = $subject['name'];
        }

        $lessons_api = $homeworks['lessons'];

        $dic = getDic();
        $lessons = [];
        $result = "{$dic['days_of_week'][$date->format('w')]}:\n";
        foreach ($lessons_api as $lesson) {
            $lessons[$lesson['number']] = [
                'subjectName' => $subjects[$lesson['subjectId']],
                'id' => $lesson['id'],
                'works' => $works[$lesson['id']]
            ];
        }
        ksort($lessons);

        foreach ($lessons as $number => $lesson) {
            $result .= "{$dic['lesson_numbers'][$number]} {$lesson['subjectName']}\n";
            foreach ($lesson['works'] as $key => $work) {
                if (count($lesson['works'])-1 == $key) {
                    $result .= "{$dic['tree_icons']['b-l']} ";
                } else {
                    $result .= "{$dic['tree_icons']['v-l']} ";
                }

                $result .= "{$work}\n";
            }
        }

        return $result;
    }
}