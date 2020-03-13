<?php


namespace App\Commands;


use App\HttpRequestBuilder\HttpRequest;
use App\Operations\ContextOperation;
use App\Operations\EduGroupOperation;
use App\Operations\UserOperation;
use DateTime;

class RatingCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $user = getUser();
        $context = ContextOperation::me();
        $period = (new EduGroupOperation())->getCurrentPeriod($context->groupIds[0]);
        $avg_marks_api = HttpRequest::init('edu-groups/:edu_group_id/avg-marks/:start/:finish', [
            'url_params' => [
                'edu_group_id' => $context->groupIds[0],
                'start' => (new DateTime($period['start']))->format('Y-m-d'),
                'finish' => (new DateTime($period['finish']))->format('Y-m-d'),
            ],
            'args' => [
                'access_token' => $user->access_token
            ],
        ])->execute();
        
        $classmates_api = HttpRequest::init('edu-groups/:edu_group_id/students', [
            'url_params' => [
                'edu_group_id' => $context->groupIds[0]
            ],
            'args' => [
                'access_token' => $user->access_token
            ]
        ])->execute();

        $classmates = [];
        foreach ($classmates_api as $classmate) {
            $classmates[$classmate->id] = $classmate->shortName;
        }

        $avg_marks = [];
        foreach ($avg_marks_api as $person_marks) {
            $marks_count = 0;
            $marks_sum = 0;
            foreach ($person_marks->{'per-subject-averages'} as $person_mark) {
                $marks_sum += $person_mark->{'avg-mark-value'};
                $marks_count++;
            }

            $avg_marks[$person_marks->person] = ($marks_count > 0) ? round($marks_sum / $marks_count, 2) : 0;
        }

        asort($avg_marks);
        $avg_marks = array_reverse($avg_marks, true);

        $result = "Рейтинг класса: \n\n";

        $number = 1;
        foreach ($avg_marks as $key => $avg_mark) {
            $result .= $number . ". {$classmates[$key]} - {$avg_mark}\n";
            $number++;
        }

        return $result;
    }
}