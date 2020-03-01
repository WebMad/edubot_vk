<?php

namespace App\Bot\Commands;

use App\HttpRequestBuilder\HttpRequest;
use App\Objects\ContextObject\SubjectObject;
use App\Operations\ContextOperation;
use App\Operations\ScheduleOperation;
use App\Operations\SubjectOperation;
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
        $date_from = $date->modify('-' . ((int) $date->format('w') - 1) . ' days');
        $date_to = (clone $date_from)->modify('+ 6 days')->format('Y-m-d');
        $date_from = $date_from->format('Y-m-d');

        $context = ContextOperation::me();

        $dic = getDic();

        $edu_group_id = $context->groupIds[0];

        $subjects = [];
        $api_subjects = SubjectOperation::getSubjects($edu_group_id);

        foreach ($api_subjects as $subject) {
            $subjects[$subject->id] = $subject->name;
        }

        $result = "Расписание с $date_from до $date_to\n";
        $schedule = ScheduleOperation::getSchedule($context->personId, $edu_group_id, $date_from, $date_to);

        foreach ($schedule->days as $day) {
            $date = new DateTime($day->date);
            $result .= "\n\n";
            $result .= $dic['days_of_week'][$date->format('w')] . ": \n";
            $lessons = $day->lessons;
            foreach ($lessons as $lesson) {
                $result .= "{$dic['lesson_numbers'][$lesson->number]} {$subjects[$lesson->subjectId]} | {$lesson->hours}";
                if (!empty($lesson->place)) {
                    $result .= " | каб. {$lesson->place}";
                }
                $result .= "\n";
            }
        }

        return $result;
    }
}