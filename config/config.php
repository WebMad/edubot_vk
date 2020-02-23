<?php
defined('APP_DIR') or define('APP_DIR', dirname(__DIR__));
defined('APP_ENV') or define('APP_ENV', file_get_contents(APP_DIR . '/.env'));

$constants = parse_ini_file(APP_DIR. '/.env');
foreach ($constants as $name_const => $val_const) {
    defined($name_const) or define($name_const, $val_const);
}