<?php

namespace App\Commands;

use App\Operations\ContextOperation;
use App\Operations\HomeworkOperation;
use App\Operations\ScheduleOperation;
use App\Operations\SubjectOperation;
use DateTime;

class TomorrowScheduleCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $date = (new DateTime())->modify('+1 day')->format('Y-m-d');

        $context = ContextOperation::me();

        $dic = getDic();

        $edu_group_id = $context->groupIds[0];

        $subjects = [];
        $api_subjects = SubjectOperation::getSubjects($edu_group_id);

        foreach ($api_subjects as $subject) {
            $subjects[$subject->id] = $subject->name;
        }

        $homeworks_http = HomeworkOperation::getHomeworkByDate($context->schoolIds[0], $date, $date);
        $homeworks = [];
        foreach ($homeworks_http->works as $homework_http) {
            $homeworks[$homework_http->lesson][] = $homework_http;
        }

        $result = "Расписание на завтра: \n";
        $schedule = ScheduleOperation::getSchedule($context->personId, $edu_group_id, $date, $date);

        foreach ($schedule->days as $day) {
            $date = new DateTime($day->date);
            $result .= "\n\n";
            $result .= $dic['days_of_week'][$date->format('w')] . ": \n";
            $lessons = $day->lessons;
            if (!empty($lessons)) {
                foreach ($lessons as $lesson) {
                    $result .= "{$dic['lesson_numbers'][$lesson->number]} {$subjects[$lesson->subjectId]}";
                    if (!empty($lesson->hours)) {
                        $result .= " | {$lesson->hours}";
                    }
                    if (!empty($lesson->place)) {
                        $result .= " | каб. {$lesson->place}";
                    }
                    if (!empty($homeworks[$lesson->id])) {
                        foreach ($homeworks[$lesson->id] as $key => $homework) {
                            if (count($homeworks[$lesson->id]) == ($key + 1)) {
                                $result .= "\n{$dic['tree_icons']['b-l']} ";
                            } else {
                                $result .= "\n{$dic['tree_icons']['v-l']} ";
                            }
                            $result .= $homework->text;
                        }
                    }

                    $result .= "\n";
                }
            } else {
                $result .= "Нет уроков";
            }
        }

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}