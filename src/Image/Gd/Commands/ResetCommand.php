<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:25
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;
use Notadd\Foundation\Image\Exceptions\RuntimeException;

/**
 * Class ResetCommand.
 */
class ResetCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $backupName = $this->argument(0)->value();
        if (is_resource($backup = $image->getBackup($backupName))) {
            imagedestroy($image->getCore());
            $backup = $image->getDriver()->cloneCore($backup);
            $image->setCore($backup);

            return true;
        }
        throw new RuntimeException('Backup not available. Call backup() before reset().');
    }
}
