<?php

namespace App\Commands;

use App\HttpRequestBuilder\HttpRequest;
use App\Operations\ContextOperation;
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

        $user_info = ContextOperation::me();
        $edu_group = $user_info->eduGroups[0]->id_str;

        $today_schedule = HttpRequest::init('persons/:person_id/groups/:edu_group_id/schedules', [
            'url_params' => [
                'person_id' => $user_info->personId,
                'edu_group_id' => $edu_group->id_str,
            ],
            'args' => [
                'access_token' => $user->access_token,
                'startDate' => $date->format('Y-m-d'),
                'endDate' => $date->format('Y-m-d'),
            ],
            'is_assoc' => true,
        ])->execute()['days'][0]['lessons'];
        $school = $user_info->schools[0];
        if (!empty($today_schedule)) {
            $last_lesson = $today_schedule[count($today_schedule) - 1];
            $hours = trim(substr($last_lesson['hours'], stripos($last_lesson['hours'], '-') + 2));

            $homeworks_date = ($date >= new DateTime($hours)) ? (clone $date)->modify('+1 day') : clone $date;
        } else {
            $homeworks_date = (clone $date)->modify('+1 day');
        }
        $homeworks = HttpRequest::init('users/me/school/:school_id/homeworks', [
            'url_params' => [
                'school_id' => $school->id
            ],
            'args' => [
                'access_token' => $user->access_token,
                'startDate' => $homeworks_date->format('Y-m-d'),
                'endDate' => $homeworks_date->format('Y-m-d'),
            ],
            'is_assoc' => true,
        ])->execute();

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

        $dic = getDic();

        $lessons_api = $homeworks['lessons'];
        $lessons = [];
        $result = "{$dic['days_of_week'][$homeworks_date->format('w')]}:\n";
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
                if (count($lesson['works']) - 1 == $key) {
                    $result .= "{$dic['tree_icons']['b-l']} ";
                } else {
                    $result .= "{$dic['tree_icons']['v-l']} ";
                }

                $result .= "{$work}\n";
            }
        }

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}