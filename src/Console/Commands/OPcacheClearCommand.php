<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-11-08 16:11
 */
namespace Notadd\Foundation\Console\Commands;

use Notadd\Foundation\Console\Abstracts\Command;

/**
 * Class OPcacheClearCommand.
 */
class OPcacheClearCommand extends Command
{
    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * OPcacheStatusCommand constructor.
     *
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->prefix = function_exists('opcache_reset') ? 'opcache_' : (function_exists('accelerator_reset') ? 'accelerator_' : '');
    }

    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->setDescription('Clear OPcache.');
        $this->setName('opcache:clear');
    }

    /**
     * Command Handler.
     */
    public function handle()
    {
        if (function_exists($this->prefix . 'reset')) {
            call_user_func($this->prefix . 'reset');
        }
        $this->output->writeln('<info>Done!</info>');
    }
}
