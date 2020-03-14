<?php

namespace App\Commands;


use App\Operations\UserOperation;

class UserRolesCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = '';
        $roles = (new UserOperation())->getUserRoles(getUser()->dnevnik_user_id);
        foreach ($roles as $role) {
            $result .= $role . "\n";
        }
        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}