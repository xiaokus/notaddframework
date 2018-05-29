<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 18:04
 */
namespace Notadd\Foundation\Image\Gd\Commands;

use Notadd\Foundation\Image\Commands\AbstractCommand;
use Notadd\Foundation\Image\Exceptions\NotReadableException;
use Notadd\Foundation\Image\Gd\Color;
use Notadd\Foundation\Image\Gd\Decoder;

/**
 * Class FillCommand.
 */
class FillCommand extends AbstractCommand
{
    /**
     * Fills image with color or pattern.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $filling = $this->argument(0)->value();
        $x = $this->argument(1)->type('digit')->value();
        $y = $this->argument(2)->type('digit')->value();
        $width = $image->getWidth();
        $height = $image->getHeight();
        $resource = $image->getCore();
        try {
            $source = new Decoder();
            $tile = $source->init($filling);
            imagesettile($image->getCore(), $tile->getCore());
            $filling = IMG_COLOR_TILED;
        } catch (NotReadableException $e) {
            $color = new Color($filling);
            $filling = $color->getInt();
        }
        imagealphablending($resource, true);
        if (is_int($x) && is_int($y)) {
            $base = $image->getDriver()->newImage($width, $height)->getCore();
            imagecopy($base, $resource, 0, 0, 0, 0, $width, $height);
            imagefill($resource, $x, $y, $filling);
            imagecopy($base, $resource, 0, 0, 0, 0, $width, $height);
            $image->setCore($base);
            imagedestroy($resource);
        } else {
            imagefilledrectangle($resource, 0, 0, $width - 1, $height - 1, $filling);
        }
        isset($tile) ? imagedestroy($tile->getCore()) : null;

        return true;
    }
}
