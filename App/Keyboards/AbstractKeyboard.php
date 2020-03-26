<?php


namespace App\Keyboards;


use App\Response;

/**
 * Class AbstractButton
 * @package App\Buttons
 */
abstract class AbstractKeyboard
{
    /**
     * @var
     */
    protected $payload;
    /**
     * @var
     */
    protected $message_object;

    /**
     * @var Response
     */
    protected $response;

    /**
     * AbstractButton constructor.
     * @param Response $response
     * @param $message_object
     * @param $user
     */
    public function __construct(Response $response, $message_object)
    {
        $this->setResponse($response);
        $this->setPayload(json_decode($message_object['payload'], TRUE));
        $this->setMessageObject($message_object);
    }


    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $value
     */
    public function setResponse($value)
    {
        $this->response = $value;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param $value
     */
    public function setPayload($value)
    {
        $this->payload = $value;
    }

    /**
     * @return mixed
     */
    public function getMessageObject()
    {
        return $this->message_object;
    }

    /**
     * @param $value
     */
    public function setMessageObject($value)
    {
        $this->message_object = $value;
    }
}