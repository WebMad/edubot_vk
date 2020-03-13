<?php

namespace App\Commands;


use VK\Client\VKApiClient;

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

        (new VKApiClient())->messages()->send(ACCESS_TOKEN, [
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => 'Вы вышли из аккаунта',
            'random_id' => rand(0, 100000),
            'keyboard' => getDic()['keyboards']['help_keyboard'],
        ]);
    }
}