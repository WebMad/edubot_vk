<?php

use App\Commands\HelpCommand;
use App\Commands\HomeworkCommand;
use App\Commands\LoginCommand;
use App\Commands\LogoutCommand;
use App\Commands\MarksCommand;
use App\Commands\NewsCommand;
use App\Commands\RatingCommand;
use App\Commands\ScheduleCommand;
use App\Commands\TodayScheduleCommand;
use App\Commands\TomorrowScheduleCommand;

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