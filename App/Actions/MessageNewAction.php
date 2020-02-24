<?php

namespace App\Actions;

use App\Commands\AbstractCommand;
use App\Commands\ClassInfoCommand;
use App\Commands\LoginCommand;
use App\Commands\LogoutCommand;
use App\Commands\LessonsCommand;
use App\Commands\UserRolesCommand;
use App\Models\User;

class MessageNewAction extends AbstractAction
{
    private $commands = [
        'войти' => LoginCommand::class,
        'выйти' => LogoutCommand::class,
        'класс' => ClassInfoCommand::class,
        'моироли' => UserRolesCommand::class,
        'расписание' => LessonsCommand::class,
    ];

    /**
     * @inheritDoc
     */
    function execute($data)
    {
        $message_object = $data['object'];

        $command = '';
        $command_parts = explode(' ', $message_object['text']);
        if (isset($command_parts[0])) {
            $command = mb_strtolower($command_parts[0]);
        }

        if (array_key_exists($command, $this->commands)) {

            $user = User::where(['vk_user_id' => $message_object['from_id']])->first();
            if (!empty($user)) {
                saveUser($user);
            }

            file_put_contents(APP_DIR . '/request.txt', json_encode($data, JSON_UNESCAPED_UNICODE));

            /** @var AbstractCommand $command_class */
            $command_class = new $this->commands[$command]($message_object);
            $result = $command_class->execute();
            $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
//                'user_id' => $message_object['from_id'],
                'peer_id' => $message_object['peer_id'],
                'message' => $result,
                'random_id' => rand(0, 100000)
            ]);
        } else {
            $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
//                'user_id' => $message_object['from_id'],
                'peer_id' => $message_object['peer_id'],
                'message' => 'Команда не найдена',
                'random_id' => rand(0, 100000)
            ]);
        }

        return 'ok';
    }
}