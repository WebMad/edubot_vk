<?php
include dirname(__DIR__) . "/vendor/autoload.php";

use App\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAgreementColumnToUsers extends Migration
{

    public function up()
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->integer('personal_data_access')->comment('Статус доступа к персональным данным пользователя')->default(0);
        });

    }

}
