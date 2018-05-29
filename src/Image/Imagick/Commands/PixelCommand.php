<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 19:05
 */
namespace Notadd\Foundation\Image\Imagick\Commands;

use ImagickDraw;
use Notadd\Foundation\Image\Commands\AbstractCommand;
use Notadd\Foundation\Image\Imagick\Color;

/**
 * Class PixelCommand.
 */
class PixelCommand extends AbstractCommand
{
    /**
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $color = $this->argument(0)->required()->value();
        $color = new Color($color);
        $x = $this->argument(1)->type('digit')->required()->value();
        $y = $this->argument(2)->type('digit')->required()->value();
        $draw = new ImagickDraw();
        $draw->setFillColor($color->getPixel());
        $draw->point($x, $y);

        return $image->getCore()->drawImage($draw);
    }
}
