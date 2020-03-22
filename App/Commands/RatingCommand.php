<?php


namespace App\Commands;


use App\Factories\RatingNameFactory;
use App\HttpRequestBuilder\HttpRequest;
use App\Models\User;
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
            $classmates[$classmate->id] = $classmate;
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

        $result = "Ваша позиция в рейтинге класса: \n\n";
        $factory = new RatingNameFactory();
        $number = 1;
        $icon = getDic()['icons']['user'];
        $classmates_dnevnik_id = array_map(function($classmate) {
            return $classmate->id;
        },$classmates);
        $classmates_db = User::where('dnevnik_user_id', $classmates_dnevnik_id)
                               ->where('personal_data_access', User::DATA_ACCESSED)
                               ->get()->pluck('dnevnik_user_id')->all();
        foreach ($avg_marks as $key => $avg_mark) {
            if ($classmates[$key]->id == $context->personId
                || in_array($classmates[$key]->id, $classmates_db)) {
                $result .= $number . ". {$classmates[$key]->shortName} - {$avg_mark} {$icon}\n";
            }
            else {
                $random_name = $factory->generate();
                $result.= "{$number}. {$random_name} - {$avg_mark}\n";
            }
            $number++;
        }

        if ($user->personal_data_access == User::DATA_RESTRICT_ASK) {
            $result.="Чтобы ваше настоящее имя отображалось в списке класса, вы должны дать на это согласие.\n";
        }

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'keyboard' => getDic()['keyboards']['personal_data_keyboard'],
            'random_id' => rand(0, 100000),
        ]);
    }
}