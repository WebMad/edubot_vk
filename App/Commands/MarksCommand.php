<?php

namespace App\Commands;

use App\Operations\EduGroupOperation;
use App\Operations\UserOperation;
use DateTime;

class MarksCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $user = getUser();

        $user_operation = new UserOperation();
        $user_info = $user_operation->getUserInfo($user->dnevnik_user_id);
        $user_edu_groups = $user_operation->getUserEduGroups($user->dnevnik_user_id);

        $period = (new EduGroupOperation())->getCurrentPeriod($user_edu_groups[0]['id_str']);

        $start_marks = (new DateTime($period['start']))->format('Y-m-d');
        $finish_marks = (new DateTime($period['finish']))->format('Y-m-d');

        $dic = getDic();

        $result = '';

        $subjects_api = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$user_edu_groups[0]['id_str']}/subjects?access_token={$user->access_token}"), true);
        foreach ($subjects_api as $subject) {
            $subject_marks = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info['personId']}/subjects/{$subject['id']}/marks/{$start_marks}/{$finish_marks}?access_token={$user->access_token}"), true);
            $result .= "{$dic['icons']['notepad']} {$subject['name']}\n";
            if (!empty($subject_marks)) {
                $subject_avg_mark = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info['personId']}/reporting-periods/{$period['id']}/subjects/{$subject['id']}/avg-mark?access_token={$user->access_token}"), true);
                $result .= "{$dic['tree_icons']['v-l']} Оценки: ";
                foreach ($subject_marks as $subject_mark) {
                    $result .= $subject_mark['value'] . ' ';
                }
                $result .= "\n{$dic['tree_icons']['b-l']} Ср. балл: {$subject_avg_mark}";
            } else {
                $result .= "{$dic['tree_icons']['b-l']} Нет оценок";
            }
            $result .= "\n\n";
        }
        return $result;
    }
}