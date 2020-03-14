<?php

namespace App\Commands;

class StartCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => getMessagesTemplates()['welcome_message'],
            'random_id' => rand(0, 100000),
        ]);
    }
}