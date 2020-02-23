<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager;

class Database
{
    function __construct()
    {
        $manager = new Manager();
        $manager->addConnection(include APP_DIR . '/config/db.php');
        $manager->bootEloquent();
    }
}