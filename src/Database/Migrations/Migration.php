<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 20:41
 */
namespace Notadd\Foundation\Database\Migrations;

use Illuminate\Database\ConnectionInterface;

/**
 * Class Migration.
 */
abstract class Migration
{
    /**
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;
    
    /**
     * @var bool
     */
    public $withinTransaction = true;

    /**
     * Migration constructor.
     *
     * @param \Illuminate\Database\ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->schema = call_user_func([
            $connection,
            'getSchemaBuilder',
        ]);
    }

    /**
     * Migration's down handler.
     *
     * @return mixed
     */
    abstract public function down();

    /**
     * Get connection instance.
     *
     * @return string
     */
    public function getConnection()
    {
        return '';
    }

    /**
     * Migration's up handler.
     *
     * @return mixed
     */
    abstract public function up();
}
