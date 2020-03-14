<?php


namespace App\Commands;


use App\Response;

class StartCommand extends AbstractCommand
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = "Для начала войдите в аккаунт Дневник.ру, используя следующую команду: \n\n";
        $result .= '/войти ' . getDic()['commands']['войти']['description'] . "\n\n";
        $result .= 'Чтобы получить список команд напишите /помощь';
        return $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
    }
}