<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <260096556@qq.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 12:20
 */
namespace Notadd\Foundation\Cache\Commands;

use Illuminate\Console\Command;
use Notadd\Foundation\Cache\Queues\FlushAll;

/**
 * Class RouteClearCommand.
 */
class CacheClearCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'cache:clear';

    /**
     * @var string
     */
    protected $description = 'Clear the notadd cache';

    /**
     * RouteClearCommand constructor.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Command handler.
     */
    public function handle()
    {
        FlushAll::dispatch();
        $this->info('Notadd cache cleared!');
    }
}
