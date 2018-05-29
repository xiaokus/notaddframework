<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:00
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Imagick;
use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class LimitColorsCommand.
 */
class LimitColorsCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $count = $this->argument(0)->value();
        $matte = $this->argument(1)->value();
        $size = $image->getSize();
        $alpha = clone $image->getCore();
        $alpha->separateImageChannel(Imagick::CHANNEL_ALPHA);
        $alpha->transparentPaintImage('#ffffff', 0, 0, false);
        $alpha->separateImageChannel(Imagick::CHANNEL_ALPHA);
        $alpha->negateImage(false);
        if ($matte) {
            $mattecolor = $image->getDriver()->parseColor($matte)->getPixel();
            $canvas = new Imagick();
            $canvas->newImage($size->width, $size->height, $mattecolor, 'png');
            $image->getCore()->quantizeImage($count, Imagick::COLORSPACE_RGB, 0, false, false);
            $canvas->compositeImage($image->getCore(), Imagick::COMPOSITE_DEFAULT, 0, 0);
            $canvas->compositeImage($alpha, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
            $image->setCore($canvas);
        } else {
            $image->getCore()->quantizeImage($count, Imagick::COLORSPACE_RGB, 0, false, false);
            $image->getCore()->compositeImage($alpha, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
        }

        return true;
    }
}
