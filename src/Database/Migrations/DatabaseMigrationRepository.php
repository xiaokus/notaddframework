<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-04-21 11:33
 */
namespace Notadd\Foundation\Database\Migrations;

use Illuminate\Database\Migrations\DatabaseMigrationRepository as IlluminateDatabaseMigrationRepository;

/**
 * Class DatabaseMigrationRepository.
 */
class DatabaseMigrationRepository extends IlluminateDatabaseMigrationRepository
{
    /**
     * Get the last migration batch on path.
     *
     * @param $files
     *
     * @return array
     */
    public function getLastOnPath($files)
    {
        $query = $this->table()->whereIn('migration', $files);

        return $query->orderBy('migration', 'desc')->get()->all();
    }
}
