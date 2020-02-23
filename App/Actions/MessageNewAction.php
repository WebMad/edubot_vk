<?php

namespace App\Actions;

use App\Commands\AbstractCommand;
use App\Commands\LoginCommand;

class MessageNewAction extends AbstractAction
{
    private $commands = [
        'войти' => LoginCommand::class,
    ];

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        $message_object = $data['object'];

        $command = mb_strtolower(substr($message_object['text'], 0, stripos($message_object['text'], ' ')));
        if (array_key_exists($command, $this->commands)) {
            /** @var AbstractCommand $command_class */
            $command_class = new $this->commands[$command]($message_object['text']);
            $result = $command_class->execute();
            $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
                'user_id' => $message_object['from_id'],
                'peer_id' => $message_object['peer_id'],
                'message' => $result,
                'random_id' => rand(0, 100000)
            ]);
        } else {
            $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
                'user_id' => $message_object['from_id'],
                'peer_id' => $message_object['peer_id'],
                'message' => 'Команда не найдена',
                'random_id' => rand(0, 100000)
            ]);
        }

        return 'ok';
    }
}