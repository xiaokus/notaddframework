<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:55
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class BackupCommand.
 */
class BackupCommand extends AbstractCommand
{
    /**
     * Saves a backups of current state of image core.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $backupName = $this->argument(0)->value();
        $size = $image->getSize();
        $clone = imagecreatetruecolor($size->width, $size->height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);
        $transparency = imagecolorallocatealpha($clone, 0, 0, 0, 127);
        imagefill($clone, 0, 0, $transparency);
        imagecopy($clone, $image->getCore(), 0, 0, 0, 0, $size->width, $size->height);
        $image->setBackup($clone, $backupName);

        return true;
    }
}
