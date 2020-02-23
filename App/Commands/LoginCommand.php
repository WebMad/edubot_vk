<?php

namespace App\Commands;

use App\Exceptions\ArgumentException;

class LoginCommand extends AbstractCommand
{
    protected $command_name = 'Войти';
    private $cookies;
    /**
     * @inheritDoc
     * @throws ArgumentException
     */
    public function execute()
    {
        $args = $this->getArgs();
        if (!empty($args[0]) && !empty($args[1])) {
            $dnevnik_user_info = $this->loginDnevnik($args[0], $args[1]);
            if (!$dnevnik_user_info['result']) {
                return 'Неверный логин или пароль';
            }
            $this->cookies = $dnevnik_user_info['cookies'];
            return $this->getAccessToken(DNEVNIK_CLIENT_ID);
        }
        return 'Недостаточно аргументов';
    }

    private function loginDnevnik($login, $password)
    {
        $fields = [
            'login' => $login,
            'password' => $password,
            'exceededAttempts' => false,
            "ReturnUrl" => "https://dnevnik.ru/user/settings.aspx",
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://login.dnevnik.ru/login/esia/astrakhan',
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEFILE => "",
            CURLOPT_COOKIEJAR => $this->cookies
        ]);
        ob_start();
        curl_exec($ch);
        $result = (string)ob_get_contents();
        $cookies = curl_getinfo($ch, CURLINFO_COOKIELIST);
        ob_end_clean();
        if (stripos($result, 'Войти в Дневник.ру')) {
            return [
                'result' => false,
            ];
        }
        $start_str = mb_substr($result, mb_stripos($result, 'https://dnevnik.ru/user/settings.aspx?user=') + 43);
        $user_id = mb_substr($start_str, 0, mb_stripos($start_str, '"'));

        return [
            'result' => true,
            'user_id' => $user_id,
            'cookies' => $cookies,
        ];
    }

    private function getAccessToken($client_id)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://login.dnevnik.ru/oauth2/?access_token=0&response_type=token&client_id=$client_id&scope=Avatar,FullName,Schools,EduGroups,Lessons,Marks,Relatives,Roles,EmailAddress,Birthday,Messages&is_grated=true",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'access_token' => 0,
                'response_type' => 'token',
                'client_id' => $client_id,
                'scope' => 'Avatar,FullName,Schools,EduGroups,Lessons,Marks,Relatives,Roles,EmailAddress,Birthday,Messages',
                'is_granted' => 'true'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEFILE => $this->cookies,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => true,
        ]);
        ob_start();
        curl_exec($ch);
        $access_token = mb_substr(explode('&', urldecode(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)))[2], 10);
        ob_end_clean();
        return $access_token;
    }
}