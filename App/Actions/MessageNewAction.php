<?php

namespace App\Actions;

use App\Commands\AbstractCommand;
use App\Commands\ClassInfoCommand;
use App\Commands\HelpCommand;
use App\Commands\HomeworkCommand;
use App\Commands\LoginCommand;
use App\Commands\LogoutCommand;
use App\Commands\MarksCommand;
use App\Commands\ScheduleCommand;
use App\Commands\UserRolesCommand;
use App\Models\User;

class MessageNewAction extends AbstractAction
{
    private $commands = [
        'войти' => LoginCommand::class,
        'выйти' => LogoutCommand::class,
        'help' => HelpCommand::class,
        'класс' => ClassInfoCommand::class,
        'роли' => UserRolesCommand::class,
        'расписание' => ScheduleCommand::class,
        'дз' => HomeworkCommand::class,
        'оценки' => MarksCommand::class,
    ];

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
            if (array_key_exists($command, $this->commands)) {

                $user = User::where(['vk_user_id' => $message_object['from_id']])->first();
                if (!empty($user)) {
                    saveUser($user);
                }

                file_put_contents(APP_DIR . '/request.txt', json_encode($data, JSON_UNESCAPED_UNICODE));

                /** @var AbstractCommand $command_class */
                $command_class = new $this->commands[$command]($message_object);
                if ($command_class->getCheckAuth() && empty($user)) {
                    $result = "Необходимо войти в аккаунт Дневник.ру. Вы можете сделать это в ЛС с ботом.\n\n";
                    $result .= (new HelpCommand($message_object))->execute();
                } else {
                    $result = $command_class->execute();
                }
                $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
                    'peer_id' => $message_object['peer_id'],
                    'message' => $result,
                    'random_id' => rand(0, 100000)
                ]);

            } else {
                $this->getVKApiClient()->messages()->send(ACCESS_TOKEN, [
                    'peer_id' => $message_object['peer_id'],
                    'message' => 'Команда не найдена',
                    'random_id' => rand(0, 100000)
                ]);
            }
        }

        return 'ok';
    }
}