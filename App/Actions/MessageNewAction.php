<?php

namespace App\Actions;

use App\Models\User;

class MessageNewAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        $message_object = $data['object'];
        $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
            'user_id' => $message_object['from_id'],
            'peer_id' => $message_object['peer_id'],
            'message' => $message_object['text'],
            'random_id' => rand(0, 100000)
        ]);
        User::create([
            'login' => '123',
            'password' => '123',
            'access_token' => '123',
            'user_id' => '123',
        ]);
    }
}