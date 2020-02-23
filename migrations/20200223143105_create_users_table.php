<?php

include dirname(__DIR__) . "/vendor/autoload.php";

use Framework\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('login');
            $table->string('password');
            $table->string('access_token');
            $table->bigInteger('user_id');
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('users');
    }
}
