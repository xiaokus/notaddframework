<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-16 21:30
 */
namespace Notadd\Foundation\Permission\Commands;

use Illuminate\Support\Collection;
use Notadd\Foundation\Console\Abstracts\Command;
use Notadd\Foundation\Permission\Permission;

/**
 * Class PermissionCommand.
 */
class PermissionCommand extends Command
{
    /**
     * @var array
     */
    protected $headers = [
        'Identification',
        'Description',
    ];

    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->setDescription('Show permission list.');
        $this->setName('permission:list');
    }

    /**
     * Command Handler.
     *
     * @return bool
     */
    public function handle()
    {
        $data = new Collection();
        $this->container->make('permission')->permissions()->each(function (Permission $permission, $identification) use ($data) {
            $data->push([
                $identification,
                $permission->description(),
            ]);
        });
        $this->table($this->headers, $data->toArray());

        return true;
    }
}
