<?php


namespace App\Operations;


/**
 * Авторизация пользователя в системе
 *
 * Class AuthOperation
 * @package App\Operations
 */
class AuthOperation
{

    /**
     * Получить адрес cookie файла для взаимодействия с дневник.ру
     *
     * @param $vk_user_id
     * @return string
     */
    static public function getCookieFile($vk_user_id)
    {
        return APP_DIR . '/temp/cookie_' . $vk_user_id . '.txt';
    }

    /**
     * Авторизует пользователя через дневник.ру
     *
     * @param $login
     * @param $password
     * @param $vk_user_id
     * @return array
     */
    static public function loginDnevnik($login, $password, $vk_user_id)
    {
        $fields = [
            'login' => $login,
            'password' => $password,
            'exceededAttempts' => false,
            "ReturnUrl" => "https://dnevnik.ru/user/settings.aspx",
        ];

        $cookie_file = self::getCookieFile($vk_user_id);

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

    /**
     * Получить токен для работы с api дневник.ру
     *
     * @param $vk_user_id
     * @return false|string
     */
    static public function getDnevnikAccessToken($vk_user_id)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://login.dnevnik.ru/oauth2/?access_token=0&response_type=token&client_id=" . DNEVNIK_CLIENT_ID . "&scope=Avatar,FullName,Schools,EduGroups,Lessons,Marks,Relatives,Roles,EmailAddress,Birthday,Messages&is_grated=true",
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'access_token' => 0,
                'response_type' => 'token',
                'client_id' => DNEVNIK_CLIENT_ID,
                'scope' => 'Avatar,FullName,Schools,EduGroups,Lessons,Marks,Relatives,Roles,EmailAddress,Birthday,Messages',
                'is_granted' => 'true'
            ],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEFILE => self::getCookieFile($vk_user_id),
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