<?php

namespace App\Commands;

use App\Response;

class CommandNotFoundCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => getMessagesTemplates()['command_not_found'],
            'random_id' => rand(0, 100000),
        ]);
    }
}