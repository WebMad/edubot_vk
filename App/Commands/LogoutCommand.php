<?php

namespace App\Commands;


class LogoutCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $user = getUser();
        if ($user) {
            $user->delete();
        }

        return 'Выход произведен';
    }
}