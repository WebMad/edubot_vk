<?php

use Framework\App;

defined('APP_DIR') or define('APP_DIR', dirname(__DIR__));
defined('APP_ENV') or define('APP_ENV', file_get_contents(APP_DIR . '/.env'));

require '../helpers/default.php';
require '../vendor/autoload.php';
require '../config/db.php';

$app = new App();
$app->start();