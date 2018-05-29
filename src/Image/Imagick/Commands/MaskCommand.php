<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:01
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use Imagick;
use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class MaskCommand.
 */
class MaskCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $mask_source = $this->argument(0)->value();
        $mask_w_alpha = $this->argument(1)->type('bool')->value(false);
        $imagick = $image->getCore();
        $mask = $image->getDriver()->init($mask_source);
        $image_size = $image->getSize();
        if ($mask->getSize() != $image_size) {
            $mask->resize($image_size->width, $image_size->height);
        }
        $imagick->setImageMatte(true);
        if ($mask_w_alpha) {
            $imagick->compositeImage($mask->getCore(), Imagick::COMPOSITE_DSTIN, 0, 0);
        } else {
            $original_alpha = clone $imagick;
            $original_alpha->separateImageChannel(Imagick::CHANNEL_ALPHA);
            $mask_alpha = clone $mask->getCore();
            $mask_alpha->compositeImage($mask->getCore(), Imagick::COMPOSITE_DEFAULT, 0, 0);
            $mask_alpha->separateImageChannel(Imagick::CHANNEL_ALL);
            $original_alpha->compositeImage($mask_alpha, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
            $imagick->compositeImage($original_alpha, Imagick::COMPOSITE_DSTIN, 0, 0);
        }

        return true;
    }
}
