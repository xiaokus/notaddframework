<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-05 22:10
 */
namespace Notadd\Foundation\Extension\Commands;

use Notadd\Foundation\Console\Abstracts\Command;
use Notadd\Foundation\Extension\Abstracts\Installer;
use Notadd\Foundation\Extension\Extension;

/**
 * Class InstallCommand.
 */
class InstallCommand extends Command
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('To install a extension by identification');
        $this->setName('extension:install');
    }

    /**
     * Command handler.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $extensions = $this->extension->repository()->filter(function (Extension $extension) {
            return $extension->get('require.install') == true;
        });
        $extensions->each(function (Extension $extension) {
            $installer = $extension->get('namespace') . 'Installer';
            if (class_exists($installer)) {
                $installer = new $installer;
                if ($installer instanceof Installer) {
                    $installer->handle();
                    $key = 'extension.' . $extension->identification() . '.installed';
                    $this->setting->set($key, true);
                    $key = 'extension.' . $extension->identification() . '.require.install';
                    $this->setting->set($key, false);
                }
            }
        });
        $this->cache->tags('notadd')->flush();
        $this->info('已安装以下拓展：');
        $extensions->each(function (Extension $extension) {
            $this->info($extension->identification());
        });

        return true;
    }
}
