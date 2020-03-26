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
        $response = new Response();
        $message_object = $data['object'];
        $user = User::where(['vk_user_id' => $message_object['from_id']])->first();
        if (!empty($user)) {
            saveUser($user);
        }
        if (isset($message_object['payload']))
        {
            $payload = json_decode($message_object['payload'], TRUE);
            $keyboard_full_name = 'App\Keyboards\\' . str_replace('_', '', ucwords($payload['keyboard'], '_'));
            if (class_exists($keyboard_full_name)) {
                $button_full_name = str_replace('_', '', ucwords($payload['button'], '_')) . 'Button';
                $keyboard = new $keyboard_full_name($response, $message_object);
                $response = $keyboard->$button_full_name();
            }
        }
        if (substr($message_object['text'], 0, mb_strlen(COMMAND_PREFIX)) == COMMAND_PREFIX) {
            $command = '';
            $command_parts = explode(' ', substr($message_object['text'], mb_strlen(COMMAND_PREFIX)));
            if (isset($command_parts[0])) {
                $command = mb_strtolower($command_parts[0]);
            }
            $commands = getDic()['commands'];
            if (array_key_exists($command, $commands)) {

                /** @var AbstractCommand $command_class */
                $command_class = new $commands[$command]['class']($response, $message_object);
                if ($command_class->getCheckAuth() && empty($user)) {
                    $response = (new NeedLoginCommand($response, $message_object))->execute();
                } else {
                    return $command_class->execute();
                }
            } else {
                $response = (new CommandNotFoundCommand($response, $message_object))->execute();
            }
        }

        if (isset($message_object['payload'])) {
            $button = json_decode($message_object['payload'], true);
            if ($button['command'] == 'start') {
                $response = (new StartCommand($response, $message_object))->execute();
            }
        }

        return $response;
    }
}