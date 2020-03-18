<?php

namespace App\Commands;

use App\Models\User;
use App\Operations\AuthOperation;
use VK\Client\VKApiClient;

class LoginCommand extends AbstractCommand
{
    protected $command_name = 'Войти';
    private $cookie_file;

    protected $check_auth = false;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $args = $this->getArgs();
        $peer_id = $this->getMessageObject()['peer_id'];
        if (getUser()) {
            return $this->getResponse()->addMessage([
                'peer_id' => $peer_id,
                'message' => 'Вы уже авторизованы',
                'random_id' => rand(0, 100000),
            ]);
        }
        if (!empty($args[0]) && !empty($args[1])) {
//            AuthOperation::loginDnevnik($args[0], $args[1], $);
            $dnevnik_user_info = $this->loginDnevnik($args[0], $args[1]);
            if (!$dnevnik_user_info['result']) {
                return $this->getResponse()->addMessage([
                    'peer_id' => $peer_id,
                    'message' => 'Неверный логин или пароль',
                    'random_id' => rand(0, 100000),
                ]);
            }
            $this->cookie_file = $dnevnik_user_info['cookie_file'];
            $access_token = AuthOperation::getDnevnikAccessToken($this->getMessageObject()['from_id']);

            User::create([
                'login' => $args[0],
                'password' => $args[1],
                'vk_user_id' => $peer_id,
                'access_token' => $access_token,
                'dnevnik_user_id' => $dnevnik_user_info['user_id'],
                'cookie_file' => $this->cookie_file,
            ]);

            (new VKApiClient())->messages()->send(ACCESS_TOKEN, [
                'peer_id' => $peer_id,
                'message' => 'Вход выполнен',
                'random_id' => rand(0, 100000),
                'keyboard' => getDic()['keyboards']['main_keyboard'],
            ]);
            return $this->getResponse();
        }
        return $this->getResponse()->addMessage([
            'peer_id' => $peer_id,
            'message' => 'Недостаточно аргументов',
            'random_id' => rand(0, 100000),
        ]);
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
}