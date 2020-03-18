<?php

namespace App\Commands;

use App\Response;

/**
 * Class AbstractCommand
 * @package App\Commands
 */
abstract class AbstractCommand
{

    /**
     * @var array
     */
    protected $args;
    /**
     * @var
     */
    protected $text;
    /**
     * @var
     */
    protected $command_name;
    /**
     * @var
     */
    private $message_object;
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var bool
     */
    protected $check_auth = true;

    /**
     * AbstractCommand constructor.
     * @param Response $response
     * @param $message_object
     */
    public function __construct(Response $response, $message_object)
    {
        $this->args = explode(' ', mb_substr($message_object['text'], mb_strlen($this->getCommandName()) + mb_strlen(COMMAND_PREFIX) + 1));
        $this->setMessageObject($message_object);
        $this->setText($message_object['text']);
        $this->setResponse($response);
    }

    /**
     * Выполнить команду
     *
     * @return Response
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

    /**
     * @return mixed
     */
    public function getMessageObject()
    {
        return $this->message_object;
    }

    /**
     * @param $message_object
     */
    public function setMessageObject($message_object)
    {
        $this->message_object = $message_object;
    }

    /**
     * @return bool
     */
    public function getCheckAuth()
    {
        return $this->check_auth;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    /**
     * @param mixed $command_name
     */
    public function setCommandName($command_name)
    {
        $this->command_name = $command_name;
    }

    /**
     * @param bool $check_auth
     */
    public function setCheckAuth(bool $check_auth)
    {
        $this->check_auth = $check_auth;
    }

}