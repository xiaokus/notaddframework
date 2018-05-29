<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-31 19:18
 */
namespace Notadd\Foundation\Addon\Commands;

use Notadd\Foundation\Console\Abstracts\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class GenerateCommand.
 */
class GenerateCommand extends Command
{
    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of a extension.');
        $this->setDescription('To generate a extension from template.');
        $this->setName('extension:generate');
    }

    /**
     * Command handler.
     *
     * @return bool
     */
    public function handle()
    {
        return true;
    }
}
