<?php

return [
    'personal_data_keyboard' => json_encode([
        'inline' => true,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'personal_data_access',
                        ]),
                        "label" => "Хорошо",
                    ],
                    'color' => 'positive',
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'personal_data_restrict_ask',
                        ]),
                        "label" => "Нет",
                    ],
                    'color' => 'negative',
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'personal_data_restrict',
                        ]),
                        "label" => "Нет, больше не спрашивать",
                    ],
                    'color' => 'primary',
                ]
            ]
        ]
    ]),
    'help_keyboard' => json_encode([
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'help'
                        ]),
                        "label" => "/помощь",
                    ],
                    'color' => 'primary'
                ]
            ]
        ]
    ]),
    'main_keyboard' => json_encode([
        'one_time' => false,
        'buttons' => [
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'week'
                        ]),
                        "label" => "/неделя",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'today'
                        ]),
                        "label" => "/сегодня",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'tomorrow'
                        ]),
                        "label" => "/завтра",
                    ],
                    'color' => 'primary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'marks'
                        ]),
                        "label" => "/оценки",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'homework'
                        ]),
                        "label" => "/ДЗ",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'all_marks'
                        ]),
                        "label" => "/оценки все",
                    ],
                    'color' => 'primary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'news'
                        ]),
                        "label" => "/новости",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'rating'
                        ]),
                        "label" => "/рейтинг",
                    ],
                    'color' => 'primary'
                ]
            ],
            [
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'help'
                        ]),
                        "label" => "/помощь",
                    ],
                    'color' => 'primary'
                ],
                [
                    'action' => [
                        'type' => 'text',
                        'payload' => json_encode([
                            'button' => 'logout'
                        ]),
                        "label" => "/выйти",
                    ],
                    'color' => 'primary'
                ]
            ]
        ]
    ]),
];