<?php
include "config.php";
return [
    'paths' => [
        'migrations' => 'migrations',
        'Framework' => 'Framework'
    ],
    'migration_base_class' => 'Framework\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => DB_DRIVER,
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASSWORD,
            'port' => DB_PORT
        ]
    ]
];