<?php


namespace App\Commands;


use App\Response;

class NeedLoginCommand extends AbstractCommand
{

    public function __construct(Response $response, $message_object)
    {
        $this->setCheckAuth(false);
        parent::__construct($response, $message_object);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = "Необходимо войти в аккаунт Дневник.ру. Вы можете сделать это в ЛС с ботом.";
        $this->getResponse()->addMessage([
            'peer_id' => $this->getMessageObject()['peer_id'],
            'message' => $result,
            'random_id' => rand(0, 100000),
        ]);
        $this->setResponse((new HelpCommand($this->getResponse(), $this->getMessageObject()))->execute());
        return $this->getResponse();
    }
}