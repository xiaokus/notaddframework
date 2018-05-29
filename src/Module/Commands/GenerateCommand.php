<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-12-28 11:13
 */
namespace Notadd\Foundation\Module\Commands;

use Notadd\Foundation\Console\Abstracts\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class GenerateCommand.
 */
class GenerateCommand extends Command
{
    /**
     * Configure command.
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of module.');
        $this->setDescription('To generate a module from template.');
        $this->setName('module:generate');
    }

    /**
     * Command handler.
     *
     * @return bool
     */
    public function handle(): bool
    {
        return true;
    }
}
