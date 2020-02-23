<?php

use framework\App;

defined('APP_DIR') or define('APP_DIR', dirname(__DIR__));
defined('APP_ENV') or define('APP_ENV', file_get_contents(APP_DIR . '/.env'));

require '../helpers/default.php';

$app = new App();
$app->start();