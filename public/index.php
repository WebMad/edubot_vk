<?php

use App\App;

require '../config/config.php';
require '../helpers/default.php';
require '../vendor/autoload.php';
require '../config/db.php';

$app = new App();
$app->start();