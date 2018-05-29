<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 21:01
 */
namespace Notadd\Foundation\Database;

use Illuminate\Database\MigrationServiceProvider as IlluminateMigrationServiceProvider;
use Notadd\Foundation\Database\Migrations\DatabaseMigrationRepository;
use Notadd\Foundation\Database\Migrations\MigrationCreator;
use Notadd\Foundation\Database\Migrations\Migrator;

/**
 * Class MigrationServiceProvider.
 */
class MigrationServiceProvider extends IlluminateMigrationServiceProvider
{
    /**
     * Register the migration creator.
     */
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function ($app) {
            return new MigrationCreator($app, $app['files']);
        });
    }

    /**
     * Register the migrator service.
     */
    protected function registerMigrator()
    {
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new Migrator($app, $repository, $app['db'], $app['files']);
        });
    }

    /**
     * Register the migration repository service.
     *
     * @return void
     */
    protected function registerRepository()
    {
        $this->app->singleton('migration.repository', function ($app) {
            $table = $app['config']['database.migrations'];

            return new DatabaseMigrationRepository($app['db'], $table);
        });
    }
}
