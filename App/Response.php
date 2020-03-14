<?php

namespace App;

use VK\Client\VKApiClient;

/**
 * Class Response
 * @package App
 */
class Response
{
    /**
     * Тело ответа
     * @var
     */
    protected $body;

    /**
     * Отправляемые сообщенияя
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Response constructor.
     * @param string $body
     * @param array $messages
     */
    public function __construct($body = 'ok', $messages = [])
    {
        $this->setBody($body);
        $this->setMessages($messages);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return Response
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     * @return Response
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function addMessage($message)
    {
        $this->messages[] = $message;
        return $this;
    }

    public function sendMessages()
    {
        $VK_api_client = new VKApiClient();
        foreach ((array)$this->getMessages() as $message) {
            $VK_api_client->messages()->send(ACCESS_TOKEN, $message);
        }
    }
}