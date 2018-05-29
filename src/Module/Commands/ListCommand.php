<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-12-21 18:12
 */
namespace Notadd\Foundation\Module\Commands;

use Illuminate\Support\Collection;
use Notadd\Foundation\Console\Abstracts\Command;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleManager;

/**
 * Class ListCommand.
 */
class ListCommand extends Command
{
    /**
     * @var array
     */
    protected $headers = [
        'Module Name',
        'Author',
        'Description',
        'Module Path',
        'Entry',
        'Status',
    ];

    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->setDescription('Show module list.');
        $this->setName('module:list');
    }

    /**
     * Command Handler.
     *
     * @param \Notadd\Foundation\Module\ModuleManager $manager
     *
     * @return bool
     */
    public function handle(ModuleManager $manager): bool
    {
        $modules = $manager->repository();
        $list = new Collection();
        $this->info('Modules list:');
        $modules->each(function (Module $module, $path) use ($list) {
            $list->push([
                $module->identification(),
                collect($module->author())->first(),
                $module->description(),
                $path,
                $module->service(),
                'Normal',
            ]);
        });
        $this->table($this->headers, $list->toArray());

        return true;
    }
}