<?php


namespace App\Commands;


use App\Operations\UserOperation;

class ClassInfoCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $user = getUser();

        $dnevnik_user = (new UserOperation())->getUserInfo($user->dnevnik_user_id);
        $class_info = json_decode(file_get_contents("https://api.dnevnik.ru/v2.0/persons/1153878/edu-groups?access_token={$user->access_token}&person={$dnevnik_user['personId']}"), true);
//        return json_encode($class_info);
        $result = '';
        foreach ($class_info as $group) {
            if ($group['type'] == 'Group') {
                $result .= 'Класс';
            } elseif ($group['type'] == 'Subgroup') {
                $result .= 'Подруппа';
            }
            $result .= ": {$group['fullName']} \n";
            $result .= "Предметы: \n";
            foreach ($group['subjects'] as $subject) {
                $result .= " - {$subject['name']}\n";
            }
            $result .= "---------------------------------------\n";
        }


        return $result;
    }
}