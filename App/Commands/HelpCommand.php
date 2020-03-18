<?php

namespace App\Commands;

class HelpCommand extends AbstractCommand
{
    protected $command_name = 'Войти';

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

        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000)
        ]);
    }
}