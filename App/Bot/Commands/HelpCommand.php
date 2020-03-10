<?php

namespace App\Bot\Commands;

class HelpCommand extends AbstractCommand
{
    protected $command_name = 'Войти';
    private $cookie_file;

    protected $check_auth = false;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = "Список команд:\n\n";
        $dic = getDic();
        $commands = $dic['commands'];
        foreach ($commands as $key => $command) {
            $result .= "{$dic['icons']['pencil']} " . COMMAND_PREFIX . "$key {$command['description']}\n";
        }

        return $result;
    }
}