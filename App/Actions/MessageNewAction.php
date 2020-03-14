<?php

namespace App\Actions;

use App\Commands\AbstractCommand;
use App\Commands\CommandNotFoundCommand;
use App\Commands\NeedLoginCommand;
use App\Commands\StartCommand;
use App\Models\User;
use App\Response;

class MessageNewAction extends AbstractAction
{
    /**
     * @inheritDoc
     */
    function execute($data)
    {
        $message_object = $data['object'];
        if (substr($message_object['text'], 0, mb_strlen(COMMAND_PREFIX)) == COMMAND_PREFIX) {
            $command = '';
            $command_parts = explode(' ', substr($message_object['text'], mb_strlen(COMMAND_PREFIX)));
            if (isset($command_parts[0])) {
                $command = mb_strtolower($command_parts[0]);
            }
            $commands = getDic()['commands'];
            if (array_key_exists($command, $commands)) {

                $user = User::where(['vk_user_id' => $message_object['from_id']])->first();
                if (!empty($user)) {
                    saveUser($user);
                }

                /** @var AbstractCommand $command_class */
                $command_class = new $commands[$command]['class'](new Response(), $message_object);
                if ($command_class->getCheckAuth() && empty($user)) {
                    return (new NeedLoginCommand(new Response(), $message_object))->execute();
                } else {
                    return $command_class->execute();
                }
            } else {
                return (new CommandNotFoundCommand(new Response(), $message_object))->execute();
            }
        }

        if (isset($message_object['payload'])) {
            $button = json_decode($message_object['payload'], true);
            if ($button['command'] == 'start') {
                return (new StartCommand(new Response(), $message_object))->execute();
            }
        }

        return new Response();
    }
}