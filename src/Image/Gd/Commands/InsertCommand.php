<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:17
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;
use Notadd\Foundation\Image\Image;

/**
 * Class InsertCommand.
 */
class InsertCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $source = $this->argument(0)->required()->value();
        $position = $this->argument(1)->type('string')->value();
        $x = $this->argument(2)->type('digit')->value(0);
        $y = $this->argument(3)->type('digit')->value(0);
        $watermark = $image->getDriver()->init($source);
        $image_size = $image->getSize()->align($position, $x, $y);
        $watermark_size = $watermark->getSize()->align($position);
        $target = $image_size->relativePosition($watermark_size);
        imagealphablending($image->getCore(), true);

        return imagecopy($image->getCore(), $watermark->getCore(), $target->x, $target->y, 0, 0, $watermark_size->width,
            $watermark_size->height);
    }
}
