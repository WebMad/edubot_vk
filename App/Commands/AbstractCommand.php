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

    public function __construct($text)
    {
        $this->args = explode(' ', mb_substr($text, mb_strlen($this->getCommandName()) + 1));
        $this->setText($text);
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
}