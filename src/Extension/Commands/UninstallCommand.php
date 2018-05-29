<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-06 00:11
 */
namespace Notadd\Foundation\Extension\Commands;

use Notadd\Foundation\Console\Abstracts\Command;
use Notadd\Foundation\Extension\Abstracts\Uninstaller;
use Notadd\Foundation\Extension\Extension;

/**
 * Class UninstallCommand.
 */
class UninstallCommand extends Command
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('To uninstall a extension by identification');
        $this->setName('extension:uninstall');
    }

    /**
     * Command handler.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $extensions = $this->extension->repository()->filter(function (Extension $extension) {
            return $extension->get('require.uninstall') == true;
        });
        $extensions->each(function (Extension $extension) {
            $uninstaller = $extension->get('namespace') . 'Uninstaller';
            if (class_exists($uninstaller)) {
                $uninstaller = new $uninstaller;
                if ($uninstaller instanceof Uninstaller) {
                    $uninstaller->handle();
                    $key = 'extension.' . $extension->identification() . '.installed';
                    $this->setting->set($key, false);
                    $key = 'extension.' . $extension->identification() . '.require.uninstall';
                    $this->setting->set($key, false);
                }
            }
        });
        $this->cache->tags('notadd')->flush();
        $this->info('已卸载以下拓展：');
        $extensions->each(function (Extension $extension) {
            $this->info($extension->identification());
        });

        return true;
    }
}