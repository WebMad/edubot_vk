<?php


namespace framework;


use app\Bootstrap;
use VK\Client\VKApiClient;
use VK\OAuth\VKOAuth;

class App
{
    /**
     * @var Bootstrap
     */
    private $bootstrap;

    public function __construct()
    {
        $this->bootstrap = new Bootstrap();
    }

    public function start()
    {
        $this->bootstrap->init();
    }

    public function getSession($session_name)
    {
        $session_parts = explode('.', $session_name);
        $session = $_SESSION;
        foreach ($session_parts as $session_part) {
            if (isset($session[$session_part])) {
                $session = $session[$session_part];
            } else {
                return false;
            }
        }
        return $session;
    }

    public function setSession($session_name, $value)
    {
        $session_parts = explode('.', $session_name);
        $session = &$_SESSION;
        foreach ($session_parts as $session_part) {
            $session = &$session[$session_part];
        }
        $session = $value;
        return $session;
    }
}