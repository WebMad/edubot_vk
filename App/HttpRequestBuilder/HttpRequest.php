<?php

namespace App\HttpRequestBuilder;

/**
 * Class HttpRequest
 * @package App\HttpRequestBuilder
 */
class HttpRequest
{

    /**
     * @var
     */
    private $url;
    /**
     * @var
     */
    private $args;
    /**
     * @var
     */
    private $url_params;

    /**
     * @var
     */
    private $is_json_decode;

    /**
     * @var
     */
    private $is_assoc;

    /**
     * @var
     */
    private $base_url;

    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $curl_options;

    /**
     * Метод POST
     */
    const POST_METHOD = 'post';
    /**
     * Метод get
     */
    const GET_METHOD = 'get';

    /**
     * HttpRequest constructor.
     * @param $url
     * @param array $params
     */
    public function __construct($url, $params = [])
    {
        $url_params = (isset($params['url_params'])) ? $params['url_params'] : [];
        $args = (isset($params['args'])) ? $params['args'] : [];
        $is_json_decode = (isset($params['is_json_decode'])) ? $params['is_json_decode'] : true;
        $is_assoc = (isset($params['is_assoc'])) ? $params['is_assoc'] : false;
        $base_url = (isset($params['base_url'])) ? $params['base_url'] : 'https://api.dnevnik.ru/v2.0/';
        $method = (isset($params['method'])) ? $params['method'] : self::GET_METHOD;
        $curl_options = isset($params['curl_options']) ? $params['curl_options'] : [];

        $this->setUrlParams($url_params);
        $this->setArgs($args);
        $this->setUrl($url);
        $this->setIsJsonDecode($is_json_decode);
        $this->setIsAssoc($is_assoc);
        $this->setBaseUrl($base_url);
        $this->setMethod($method);
        $this->setCurlOptions($curl_options);

        return $this;
    }

    /**
     * @param $url
     * @param array $params
     * @return HttpRequest
     */
    static public function init($url, $params = [])
    {
        return new self($url, $params);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        $url = $this->buildUrl();

        if ($this->getMethod() == self::POST_METHOD) {
            $args[CURLOPT_POSTFIELDS] = $this->getArgs();
            $args[CURLOPT_POST] = $this->getMethod() == self::POST_METHOD;
        }
        $args[CURLOPT_FOLLOWLOCATION] = true;
        $args[CURLOPT_URL] = $url;
        $args = $this->getCurlOptions() + $args;

        $ch = curl_init();
        curl_setopt_array($ch, $args);

        ob_start();
        curl_exec($ch);
        $result = ob_get_contents();
        ob_end_clean();
        curl_close($ch);

        if ($this->getIsJsonDecode()) {
            $result = json_decode($result, $this->getIsAssoc());
        }
        return $result;
    }

    /**
     * @return string
     */
    public function buildUrl()
    {
        $url = $this->getUrl();
        $url_params = $this->getUrlParams();
        foreach ($url_params as $key => $param) {
            $url = str_replace(':' . $key, $param, $url);
        }

        if ($this->getMethod() == self::GET_METHOD) {
            $url .= '?' . http_build_query($this->getArgs());
        }

        return $this->getBaseUrl() . $url;
    }

    /**
     * @return mixed
     */
    public function getUrlParams()
    {
        return $this->url_params;
    }

    /**
     * @param mixed $url_params
     */
    public function setUrlParams($url_params)
    {
        $this->url_params = $url_params;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param mixed $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public function getIsJsonDecode()
    {
        return $this->is_json_decode;
    }

    /**
     * @param mixed $is_json_decode
     */
    public function setIsJsonDecode($is_json_decode)
    {
        $this->is_json_decode = $is_json_decode;
    }

    /**
     * @return mixed
     */
    public function getIsAssoc()
    {
        return $this->is_assoc;
    }

    /**
     * @param mixed $is_assoc
     */
    public function setIsAssoc($is_assoc)
    {
        $this->is_assoc = $is_assoc;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param mixed $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getCurlOptions()
    {
        return $this->curl_options;
    }

    /**
     * @param mixed $curl_options
     */
    public function setCurlOptions($curl_options)
    {
        $this->curl_options = $curl_options;
    }

}