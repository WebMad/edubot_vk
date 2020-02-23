<?php

include dirname(__DIR__) . "/vendor/autoload.php";

use App\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID записи');
            $table->string('login')->comment('Логин из дненивка');
            $table->string('password')->comment('Пароль из дневник');
            $table->string('access_token')->comment('Токен для API дневника');
            $table->text('cookie')->comment('Куки пользователя');
            $table->bigInteger('dnevnik_user_id')->comment('ID пользователя в дневник ру');
            $table->bigInteger('vk_user_id')->comment('ID пользователя в ВК');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists('users');
    }
}
