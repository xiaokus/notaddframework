<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:22
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class OpacityCommand.
 */
class OpacityCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $transparency = $this->argument(0)->between(0, 100)->required()->value();
        $size = $image->getSize();
        $mask_color = sprintf('rgba(0, 0, 0, %.1f)', $transparency / 100);
        $mask = $image->getDriver()->newImage($size->width, $size->height, $mask_color);
        $image->mask($mask->getCore(), true);

        return true;
    }
}
