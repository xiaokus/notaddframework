<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 12:10
 */
namespace Notadd\Foundation\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class EnvironmentCommand.
 */
class EnvironmentCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'env';

    /**
     * @var string
     */
    protected $description = 'Display the current framework environment';

    /**
     * Command handler.
     */
    public function handle()
    {
        $this->line('<info>Current application environment:</info> <comment>' . $this->laravel['env'] . '</comment>');
    }
}
