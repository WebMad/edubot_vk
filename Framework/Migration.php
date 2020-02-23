<?php
namespace Framework;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Phinx\Migration\AbstractMigration;

/**
 * Class Migration
 * @package Framework
 */
class Migration extends AbstractMigration
{
    /**
     * @var Manager
     */
    public $manager;

    /**
     * @var Builder
     */
    public $schema;

    /**
     *
     */
    public function init()
    {
        $this->manager = new Manager();
        $this->manager->addConnection([
            'driver'    => DB_DRIVER,
            'host'      => DB_HOST,
            'port'      => DB_PORT,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);

        $this->manager->bootEloquent();
        $this->manager->setAsGlobal();
        $this->schema = $this->manager->schema();
    }
}