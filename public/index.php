<?php
session_start();
use App\App;

require '../config/config.php';
require '../helpers/default.php';
require '../vendor/autoload.php';

$app = new App();
$app->start();