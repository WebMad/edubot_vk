<?php

namespace App;

use VK\Client\VKApiClient;

abstract class AbstractAction
{
    private $content_type = 'text/html';
    private $charset = 'utf-8';

    /**
     * @var VKApiClient
     */
    private $VK_api_client;

    public function __construct()
    {
        $this->VK_api_client = new VKApiClient();
    }

    /**
     * Обработка события
     *
     * @param $data
     * @return mixed
     */
    abstract function execute($data);

    /**
     * Возвратить данные в формате json
     *
     * @param $data
     * @return false|string
     */
    protected function asJson($data)
    {
        $this->setContentType('application/json');
        return json_encode($data);
    }

    public function getVKApiClient()
    {
        return $this->VK_api_client;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * @param $content_type
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
}