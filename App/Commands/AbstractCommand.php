<?php

namespace App\Commands;

abstract class AbstractCommand
{

    /**
     * @var array
     */
    protected $args;
    protected $text;
    protected $command_name;
    private $message_object;

    public function __construct($message_object)
    {
        $this->args = explode(' ', mb_substr($message_object['text'], mb_strlen($this->getCommandName()) + 1));
        $this->setMessageObject($message_object);
        $this->setText($message_object['text']);
    }

    /**
     * Выполнить команду
     *
     * @return mixed
     */
    abstract public function execute();

    /**
     * @return mixed
     */
    public function getCommandName()
    {
        return $this->command_name;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    public function getMessageObject()
    {
        return $this->message_object;
    }

    public function setMessageObject($message_object)
    {
        $this->message_object = $message_object;
    }
}