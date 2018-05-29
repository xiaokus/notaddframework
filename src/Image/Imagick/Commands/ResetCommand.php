<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:05
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Imagick;
use Notadd\Foundation\Image\Commands\AbstractCommand;
use Notadd\Foundation\Image\Exceptions\RuntimeException;

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
        $backup = $image->getBackup($backupName);
        if ($backup instanceof Imagick) {
            $image->getCore()->clear();
            $backup = clone $backup;
            $image->setCore($backup);

            return true;
        }
        throw new RuntimeException("Backup not available. Call backup({$backupName}) before reset().");
    }
}
