<?php

namespace App\Bot\Commands;

use App\HttpRequestBuilder\HttpRequest;
use App\Objects\ContextObject\ContextObject;
use App\Operations\ContextOperation;
use App\Operations\EduGroupOperation;
use App\Operations\UserOperation;
use DateTime;

class MarksCommand extends AbstractCommand
{

    protected $command_name = 'оценки';

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $user = getUser();

        $user_info = ContextOperation::me();

        if ($this->getArgs()[0] == 'все') {
            return $this->getMarks($user, $user_info, true);
        }
        return $this->getMarks($user, $user_info, false);
    }

    /**
     * @param $user
     * @param $user_info ContextObject
     * @param $start_marks
     * @param $finish_marks
     * @param bool $is_all
     * @return string
     * @throws \Exception
     */
    public function getMarks($user, $user_info, $is_all = false)
    {
        $result = '';

        $user_edu_group = $user_info->eduGroups[0];

        $period = (new EduGroupOperation())->getCurrentPeriod($user_edu_group->id_str);
        if ($is_all) {
            $result .= "Ваши оценки за последний учебный период: \n\n";
            $start_marks = (new DateTime($period['start']))->format('Y-m-d');
            $finish_marks = (new DateTime($period['finish']))->format('Y-m-d');
        } else {
            $result .= "Ваши оценки за последнюю неделю:\n\n";
            $start_marks = (new DateTime())->modify('-6 days')->format('Y-m-d');
            $finish_marks = (new DateTime())->format('Y-m-d');
        }

        $dic = getDic();

        $subjects_api = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/edu-groups/{$user_edu_group->id_str}/subjects?access_token={$user->access_token}"), true);
        foreach ($subjects_api as $subject) {
            $subject_marks = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info->personId}/subjects/{$subject['id']}/marks/{$start_marks}/{$finish_marks}?access_token={$user->access_token}"), true);
            if (!empty($subject_marks) || $is_all) {
                $result .= "{$dic['icons']['notepad']} {$subject['name']}\n";
                if (!empty($subject_marks)) {
                    $subject_avg_mark = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/{$user_info->personId}/reporting-periods/{$period['id']}/subjects/{$subject['id']}/avg-mark?access_token={$user->access_token}"), true);
                    $result .= "{$dic['tree_icons']['v-l']} Оценки: ";
                    foreach ($subject_marks as $subject_mark) {
                        $result .= $subject_mark['value'] . ' ';
                    }
                    $result .= "\n{$dic['tree_icons']['b-l']} Ср. балл: {$subject_avg_mark}";
                } else {
                    $result .= "{$dic['tree_icons']['b-l']} Нет оценок";
                }
            }
            $result .= "\n\n";
        }
        return $result;
    }
}