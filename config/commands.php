<?php

use App\Bot\Commands\HelpCommand;
use App\Bot\Commands\HomeworkCommand;
use App\Bot\Commands\LoginCommand;
use App\Bot\Commands\LogoutCommand;
use App\Bot\Commands\MarksCommand;
use App\Bot\Commands\NewsCommand;
use App\Bot\Commands\RatingCommand;
use App\Bot\Commands\ScheduleCommand;
use App\Bot\Commands\TodayScheduleCommand;
use App\Bot\Commands\TomorrowScheduleCommand;

return [
    'войти' => [
        'class' => LoginCommand::class,
        'description' => '<логин> <пароль> - войти в Дневник.ру.',
    ],
    'выйти' => [
        'class' => LogoutCommand::class,
        'description' => ' - выйти из текущего аккаунта Дневник.ру.',
    ],
    'помощь' => [
        'class' => HelpCommand::class,
        'description' => ' - список команд',
    ],
    'неделя' => [
        'class' => ScheduleCommand::class,
        'description' => ' - расписание на неделю.',
    ],
    'сегодня' => [
        'class' => TodayScheduleCommand::class,
        'description' => ' - расписание, домашнее задание на сегодня.',
    ],
    'завтра' => [
        'class' => TomorrowScheduleCommand::class,
        'description' => ' - расписание, домашнее задание на завтра.',
    ],
    'дз' => [
        'class' => HomeworkCommand::class,
        'description' => ' - домашнее задание на завтра, либо на сегодня, если уроки еще не закончились.',
    ],
    'оценки' => [
        'class' => MarksCommand::class,
        'description' => ' - последние оценки. Можно добавить через пробел слово "все", тогда выведутся все оценки.',
    ],
    'новости' => [
        'class' => NewsCommand::class,
        'description' => ' - последние новости класса, школы.',
    ],
    'рейтинг' => [
        'class' => RatingCommand::class,
        'description' => ' - рейтинг класса.',
    ],
];