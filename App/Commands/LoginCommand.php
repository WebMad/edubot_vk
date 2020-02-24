<?php

namespace App\Commands;

use App\Exceptions\ArgumentException;
use App\Models\User;

class LoginCommand extends AbstractCommand
{
    protected $command_name = 'Войти';
    private $cookie_file;

    /**
     * @inheritDoc
     * @throws ArgumentException
     */
    public function execute()
    {
        $args = $this->getArgs();
        if (getUser()) {
            return 'Вы уже авторизованы';
        }
        if (!empty($args[0]) && !empty($args[1])) {
            $dnevnik_user_info = $this->loginDnevnik($args[0], $args[1]);
            if (!$dnevnik_user_info['result']) {
                return 'Неверный логин или пароль';
            }
            $this->cookie_file = $dnevnik_user_info['cookie_file'];
            $access_token = $this->getAccessToken(DNEVNIK_CLIENT_ID);

            User::create([
                'login' => $args[0],
                'password' => $args[1],
                'vk_user_id' => $this->getMessageObject()['peer_id'],
                'access_token' => $access_token,
                'dnevnik_user_id' => $dnevnik_user_info['user_id'],
                'cookie_file' => $this->cookie_file,
            ]);

            return 'Вход выполнен';
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

        $cookie_file = APP_DIR . '/temp/cookie_' . $this->getMessageObject()['from_id'] . '.txt';

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://login.dnevnik.ru/login/esia/astrakhan',
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR => $cookie_file,
        ]);
        ob_start();
        curl_exec($ch);
        $result = (string)ob_get_contents();
        ob_end_clean();
        curl_close($ch);
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
            'cookie_file' => $cookie_file,
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
            CURLOPT_COOKIEFILE => $this->cookie_file,
            CURLOPT_HEADER => true,
        ]);
        ob_start();
        curl_exec($ch);
        $access_token = substr(explode('&', ob_get_contents())[4], 28);
        ob_end_clean();
        curl_close($ch);
        return $access_token;
    }
}