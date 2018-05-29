<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-10 17:16
 */
namespace Notadd\Foundation\Image\Commands;

use Closure;

/**
 * Class LineCommand.
 */
class LineCommand extends AbstractCommand
{
    /**
     * Draws line on given image.
     *
     * @param \Notadd\Foundation\Image\Image $image
     *
     * @return bool
     */
    public function execute($image)
    {
        $x1 = $this->argument(0)->type('numeric')->required()->value();
        $y1 = $this->argument(1)->type('numeric')->required()->value();
        $x2 = $this->argument(2)->type('numeric')->required()->value();
        $y2 = $this->argument(3)->type('numeric')->required()->value();
        $callback = $this->argument(4)->type('closure')->value();
        $line_classname = sprintf('\Notadd\Foundation\Image\%s\Shapes\LineShape', $image->getDriver()->getDriverName());
        $line = new $line_classname($x2, $y2);
        if ($callback instanceof Closure) {
            $callback($line);
        }
        $line->applyToImage($image, $x1, $y1);

        return true;
    }
}
