<?php

use App\Models\User;

/**
 * @param User $user
 */
function saveUser(User $user)
{
    $GLOBALS['user'] = $user;
}

/**
 * @return bool|mixed
 */
function getUser()
{
    if (!empty($GLOBALS['user'])) {
        return $GLOBALS['user'];
    }
    return false;
}

function getDic()
{
    return require '../config/dic.php';
}

function getMessagesTemplates()
{
    return require '../config/messages_tpl.php';
}