<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:14
 */
namespace Notadd\Foundation\Image\Commands;

use Closure;

/**
 * Class EllipseCommand.
 */
class EllipseCommand extends AbstractCommand
{
    /**
     * Draws ellipse on given image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $width = $this->argument(0)->type('numeric')->required()->value();
        $height = $this->argument(1)->type('numeric')->required()->value();
        $x = $this->argument(2)->type('numeric')->required()->value();
        $y = $this->argument(3)->type('numeric')->required()->value();
        $callback = $this->argument(4)->type('closure')->value();
        $ellipse_classname = sprintf('\Notadd\Foundation\Image\%s\Shapes\EllipseShape',
            $image->getDriver()->getDriverName());
        $ellipse = new $ellipse_classname($width, $height);
        if ($callback instanceof Closure) {
            $callback($ellipse);
        }
        $ellipse->applyToImage($image, $x, $y);

        return true;
    }
}
