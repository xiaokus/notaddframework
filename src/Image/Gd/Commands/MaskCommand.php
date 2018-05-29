<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:20
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;

/**
 * Class MaskCommand.
 */
class MaskCommand extends AbstractCommand
{
    /**
     * Applies an alpha mask to an image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $mask_source = $this->argument(0)->value();
        $mask_w_alpha = $this->argument(1)->type('bool')->value(false);
        $image_size = $image->getSize();
        $canvas = $image->getDriver()->newImage($image_size->width, $image_size->height, [
            0,
            0,
            0,
            0,
        ]);
        $mask = $image->getDriver()->init($mask_source);
        $mask_size = $mask->getSize();
        if ($mask_size != $image_size) {
            $mask->resize($image_size->width, $image_size->height);
        }
        imagealphablending($canvas->getCore(), false);
        if (!$mask_w_alpha) {
            imagefilter($mask->getCore(), IMG_FILTER_GRAYSCALE);
        }
        for ($x = 0; $x < $image_size->width; ++$x) {
            for ($y = 0; $y < $image_size->height; ++$y) {
                $color = $image->pickColor($x, $y, 'array');
                $alpha = $mask->pickColor($x, $y, 'array');
                if ($mask_w_alpha) {
                    $alpha = $alpha[3];
                } else {
                    if ($alpha[3] == 0) {
                        $alpha = 0;
                    } else {
                        $alpha = floatval(round($alpha[0] / 255, 2));
                    }
                }
                if ($color[3] < $alpha) {
                    $alpha = $color[3];
                }
                $color[3] = $alpha;
                $canvas->pixel($color, $x, $y);
            }
        }
        $image->setCore($canvas->getCore());

        return true;
    }
}
