<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:25
 */

namespace Notadd\Foundation\Image\Commands;

use Closure;

/**
 * Class TextCommand.
 */
class TextCommand extends AbstractCommand
{
    /**
     * Write text on given image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $text = $this->argument(0)->required()->value();
        $x = $this->argument(1)->type('numeric')->value(0);
        $y = $this->argument(2)->type('numeric')->value(0);
        $callback = $this->argument(3)->type('closure')->value();
        $fontclassname = sprintf('\Notadd\Foundation\Image\%s\Font', $image->getDriver()->getDriverName());
        $font = new $fontclassname($text);
        if ($callback instanceof Closure) {
            $callback($font);
        }
        $font->applyToImage($image, $x, $y);

        return true;
    }
}
