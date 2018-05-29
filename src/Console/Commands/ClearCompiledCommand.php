<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 12:06
 */
namespace Notadd\Foundation\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class ClearCompiledCommand.
 */
class ClearCompiledCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'clear-compiled';

    /**
     * @var string
     */
    protected $description = 'Remove the compiled class file';

    /**
     * Command handler.
     */
    public function handle()
    {
        $servicesPath = $this->laravel->getCachedServicesPath();
        if (file_exists($servicesPath)) {
            @unlink($servicesPath);
        }
        $this->info('The compiled class file has been removed.');
    }
}
