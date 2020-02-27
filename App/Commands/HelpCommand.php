<?php

namespace App\Commands;

class HelpCommand extends AbstractCommand
{
    protected $command_name = 'Войти';
    private $cookie_file;

    protected $check_auth = false;

    /**
     * @inheritDoc
     */
    public function execute()
    {
        return <<<text
Список команд:
/help - список команд
/войти <логин> <пароль> - войти в дневник.ру
/выйти - выйти из текущего аккаунта
/класс - информация о вашем классе
/расписание - расписание на 3 дня
/дз - домашнее задание на завтра
/оценки - ваши отметки по предметам 
/роли - ваши системные роли в Дневник.ру
text;
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