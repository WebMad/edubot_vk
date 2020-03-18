<?php

namespace App\Commands;

use App\Operations\ContextOperation;
use App\Operations\ScheduleOperation;
use App\Operations\SubjectOperation;
use DateTime;

class TodayScheduleCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $date = (new DateTime())->format('Y-m-d');

        $context = ContextOperation::me();

        $dic = getDic();

        $edu_group_id = $context->groupIds[0];

        $subjects = [];
        $api_subjects = SubjectOperation::getSubjects($edu_group_id);

        foreach ($api_subjects as $subject) {
            $subjects[$subject->id] = $subject->name;
        }

        $result = "Расписание на сегодня: \n";
        $schedule = ScheduleOperation::getSchedule($context->personId, $edu_group_id, $date, $date);

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

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}