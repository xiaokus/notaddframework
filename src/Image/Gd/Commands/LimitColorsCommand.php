<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:19
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class LimitColorsCommand.
 */
class LimitColorsCommand extends AbstractCommand
{
    /**
     * Reduces colors of a given image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $count = $this->argument(0)->value();
        $matte = $this->argument(1)->value();
        $size = $image->getSize();
        $resource = imagecreatetruecolor($size->width, $size->height);
        if (is_null($matte)) {
            $matte = imagecolorallocatealpha($resource, 255, 255, 255, 127);
        } else {
            $matte = $image->getDriver()->parseColor($matte)->getInt();
        }
        imagefill($resource, 0, 0, $matte);
        imagecolortransparent($resource, $matte);
        imagecopy($resource, $image->getCore(), 0, 0, 0, 0, $size->width, $size->height);
        if (is_numeric($count) && $count <= 256) {
            imagetruecolortopalette($resource, true, $count);
        }
        $image->setCore($resource);

        return true;
    }
}
