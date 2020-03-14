<?php

namespace App\Actions;

use App\Response;

class GroupJoinAction extends AbstractAction
{

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        return (new Response())->addMessage([
            'peer_id' => $data['object']['user_id'],
            'message' => getMessagesTemplates()['command_not_found'],
            'random_id' => rand(0, 100000),
        ]);
    }
}